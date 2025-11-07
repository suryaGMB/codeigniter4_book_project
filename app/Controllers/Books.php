<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookModel;
use CodeIgniter\HTTP\ResponseInterface;

class Books extends BaseController
{
    protected $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['books'] = $this->bookModel->orderBy('id', 'DESC')->findAll();
        return view('books/index', $data);
    }

    public function create()
    {
        return view('books/create');
    }

    public function store()
    {
        helper(['form']);

        $rules = [
            'title'  => 'required|min_length[2]',
            'author' => 'required|min_length[2]',
            'price'  => 'required|decimal',
            'pdf'    => 'uploaded[pdf]|max_size[pdf,20480]|ext_in[pdf,pdf]|mime_in[pdf,application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,application/octet-stream]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $pdf = $this->request->getFile('pdf');

        if (! $pdf || ! $pdf->isValid()) {
            $msg = $pdf ? ($pdf->getErrorString() . ' (code ' . $pdf->getError() . ')') : 'No file received';
            return redirect()->back()->withInput()->with('errors', ['PDF upload failed: ' . $msg]);
        }

        $target = WRITEPATH . 'uploads/books';
        if (! is_dir($target)) {
            @mkdir($target, 0777, true);
        }

        try {
            $newName = $pdf->getRandomName();
            $pdf->move($target, $newName);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('errors', ['Could not save PDF: ' . $e->getMessage()]);
        }

        $data = [
            'title'    => $this->request->getPost('title'),
            'author'   => $this->request->getPost('author'),
            'price'    => $this->request->getPost('price'),
            'pdf_file' => $newName,
        ];

        if (! $this->bookModel->save($data)) {
            @unlink($target . '/' . $newName);
            return redirect()->back()->withInput()->with('errors', $this->bookModel->errors());
        }

        return redirect()->to('/books')->with('message', 'Book created with PDF.');
    }



    public function edit(string $token)
    {
        $id = url_to_id($token);
        if ($id < 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $book = $this->bookModel->find($id);
        if (! $book) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('books/edit', ['book' => $book]);
    }

    public function update(string $token)
    {
        // decode token -> numeric ID
        $id = url_to_id($token);
        if ($id < 1) {
            return redirect()->back()->with('error', 'Invalid ID');
        }

        $book = $this->bookModel->find($id);
        if (! $book) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Book not found');
        }

        $rules = [
            'title'  => 'required|min_length[2]|max_length[255]',
            'author' => 'required|min_length[2]|max_length[255]',
            'price'  => 'required|decimal',
            'pdf'    => 'if_exist|max_size[pdf,131072]|ext_in[pdf,pdf]|mime_in[pdf,application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,application/octet-stream]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'     => $id,
            'title'  => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'price'  => $this->request->getPost('price'),
        ];

        $pdf = $this->request->getFile('pdf');

        if ($pdf && $pdf->getError() !== UPLOAD_ERR_NO_FILE) {

            $err = $pdf->getError();
            if (in_array($err, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
                $umf = ini_get('upload_max_filesize') ?: 'unknown';
                $pms = ini_get('post_max_size') ?: 'unknown';
                return redirect()->back()->withInput()->with('errors', [
                    'pdf' => "PDF exceeds server limits: upload_max_filesize={$umf}, post_max_size={$pms}."
                ]);
            } elseif ($err !== UPLOAD_ERR_OK) {
                return redirect()->back()->withInput()->with('errors', [
                    'pdf' => $pdf->getErrorString() . " (code {$err})"
                ]);
            }

            $maxBytes = 128 * 1024 * 1024;
            if ($pdf->getSize() > $maxBytes) {
                return redirect()->back()->withInput()->with('errors', [
                    'pdf' => 'PDF is larger than the allowed 128 MB application limit.'
                ]);
            }

            if (! $pdf->isValid() || $pdf->hasMoved()) {
                return redirect()->back()->withInput()->with('errors', [
                    'pdf' => 'Uploaded file is not valid or already moved.'
                ]);
            }

            $target = WRITEPATH . 'uploads/books';
            if (! is_dir($target)) {
                @mkdir($target, 0777, true);
            }

            try {
                $newName = $pdf->getRandomName();
                $pdf->move($target, $newName);

                if (! empty($book['pdf_file'])) {
                    @unlink($target . '/' . $book['pdf_file']);
                }

                $data['pdf_file'] = $newName;
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()->with('errors', [
                    'pdf' => 'Could not save PDF: ' . $e->getMessage()
                ]);
            }
        }

        if (! $this->bookModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->bookModel->errors());
        }

        return redirect()->to('/books')->with('message', 'Book updated.');
    }

     public function delete(string $token)
    {
        $id = url_to_id($token);
        $book = $this->bookModel->find($id);
        if ($book && !empty($book['pdf_file'])) {
            @unlink(WRITEPATH . 'uploads/books/' . $book['pdf_file']);
        }

        $this->bookModel->delete($id);
        return redirect()->to('/books')->with('message', 'Book deleted.');
    }

    public function show(string $token)
    {
        $id = url_to_id($token);

        $book = $this->bookModel->find($id);
        if (! $book) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Book not found');
        }
        return view('books/show', ['book' => $book]);
    }
}
