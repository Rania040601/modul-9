<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';
        // return view('employee.index', ['pageTitle' => $pageTitle]);

        // RAW SQL QUERY
        // $employees = DB::select('
        //     select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id
        // ');

        // // Query Builder
        // $employees = DB::table('employees')
        //     ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //     ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        //     ->get();

        // ELOQUENT
        // mengambil semua data karyawan dari tabel yang terkait dengan model "Employee".
        $employees = Employee::all();
        //  untuk mengembalikan tampilan (view) dengan nama 'employee.index'yang akan menampilkan data karyawan.
        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        //  menampilkan judul halaman 'Create Employee'
        $pageTitle = 'Create Employee';
        // // RAW SQL Query
        // $positions = DB::select('select * from positions');

        // return view('employee.create', compact('pageTitle', 'positions'));

        // // Query Builder
        // $positions = DB::table('positions')->get();

        // ELOQUENT
        // mengambil semua data posisi jabatan dari tabel yang berhubungan dengan model "Position".
        $positions = Position::all();
        // untuk mengembalikan tampilan (view) dengan nama 'employee.create' yang akan menampilkan form pembuatan karyawan baru.
        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // menghandle penyimpanan data karyawan baru setelah form pembuatan
    public function store(Request $request)
    {
        // Mendefinisikan pesan kesalahan untuk validasi input
        // $messages = [
        //     'required' => ':attribute harus diisi.',
        //     'email' => 'Isi :attribute dengan format yang benar.',
        //     'numeric' => 'Isi :attribute dengan angka.'
        // ];

        // Validasi input menggunakan Validator
        // $validator = Validator::make($request->all(), [
        //     'firstName' => 'required',
        //     'lastName' => 'required',
        //     'email' => 'required|email',
        //     'age' => 'required|numeric',
        // ], $messages);

        // Jika terdapat kesalahan validasi, kembalikan kembali ke halaman sebelumnya dengan pesan kesalahan dan input yang diisi sebelumnya
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // // INSERT QUERY
        // DB::table('employees')->insert([
        //     'firstname' => $request->firstName,
        //     'lastname' => $request->lastName,
        //     'email' => $request->email,
        //     'age' => $request->age,
        //     'position_id' => $request->position,
        // ]);

         // ELOQUENT
        //  memeriksa apakah data yang diberikan sesuai dengan aturan validasi
            $employee = New Employee;
            $employee->firstname = $request->firstName;
            $employee->lastname = $request->lastName;
            $employee->email = $request->email;
            $employee->age = $request->age;
            $employee->position_id = $request->position;
        // ketika validasi berhasil data akan di simpan, $employee->save() digunakan untuk menyimpan data karyawan tersebut ke dalam database.
            // $employee->save();
        /**
         * setelah data karyawan berhasil disimpan, pengguna akan diarahkan ke halaman indeks (daftar) karyawan menggunakan perintah
         * redirect()->route('employees.index'). Halaman ini diarahkan melalui rute dengan nama "employees.index".
         */

        // return redirect()->route('employees.index');

        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }

        // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    // function show menerima parameter $id
    public function show(string $id)
    {
        //judul halaman yang akan ditampilkan 'Employee Detail'
        $pageTitle = 'Employee Detail';
        // // RAW SQL QUERY
        // $employee = collect(DB::select('
        //     select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id
        //     where employees.id = ?
        // ', [$id]))->first();

        // Query Builder
        // $employee = DB::table('employees')
        //     ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //     // memiih semua kolom dari tabel employee dan memberikan nama alias
        //     ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        //     ->where('employees.id', '=', $id)
        //     ->first();

        // ELOQUENT
        //  mencari data karyawan berdasarkan nilai parameter "$id". Metode find() digunakan untuk mencari record berdasarkan primary key-nya. Hasil query ini akan disimpan dalam variabel "$employee".
        $employee = Employee::find($id);

        // mengembalikan tampilan (view) dengan nama 'employee.show' dengan memanggil juga $pagetitle dan $employee
        return view('employee.show', compact('pageTitle', 'employee'));


    }

    /**
     * Show the form for editing the specified resource.
     */
    // menampilkan halaman edit karyawan dengan ID tertentu
    public function edit(string $id)
    {
        // judul halaman "Edit Employee".
        $pageTitle = 'Edit Employee';

        // membuat Query Builder yang akan mengakses tabel employees
        // $employee = DB::table('employees')
        //     ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //     // untuk memilih kolom yg akan diambil dari tabel employees
        //     ->select('employees.*', 'employees.id as employee_id', 'positions.name as position_name')
        //     // memastikkan bahwa id yang akan dituju sama dengan yang $id
        //     ->where('employees.id', '=', $id)
        //     ->first();

        // $positions = DB::table('positions')->get();
        //  // ELOQUENT
        // // Mengambil semua data posisi (positions) dari model Position
        // $positions = Position::all();
        // // Mengambil data karyawan dengan ID yang sesuai
        // $employee = Employee::find($id);
        // // mengembalikan tampilan employee edit
        // return view('employee.edit', compact('pageTitle', 'employee', 'positions'));

        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact('pageTitle', 'employee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    // menyimpan perubahan data karyawan setelah dilakukan editing.
    public function update(Request $request, $id)
    {
        // Mendefinisikan pesan kesalahan untuk validasi input
        $messages = [
            'required' => ':attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar.',
            'numeric' => 'Isi :attribute dengan angka.'
        ];

        // Validasi input menggunakan Validator
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        // Jika terdapat kesalahan validasi, kembalikan kembali ke halaman sebelumnya dengan pesan kesalahan dan input yang diisi sebelumnya
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Get File
        $file = $request->file('cv');

        // kondisi 1 save lanjut hapus
        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');

            // Hapus file lama jika ada
            $employee = Employee::find($id);
            if ($employee->encrypted_filename) {
                Storage::delete('public/files/'.$employee->encrypted_filename);
            }
        }

        // // logic 2 hapus lanjut save
        // if ($file != null) {
        //     $employee = Employee::find($id);
        //     $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        //     Storage::delete($encryptedFilename);
        // }
        // if ($file != null) {
        //     $originalFilename = $file->getClientOriginalName();
        //     $encryptedFilename = $file->hashName();

        //     // Store File
        //     $file->store('public/files');
        // }

        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->input('firstName');
        $employee->lastname = $request->input('lastName');
        $employee->email = $request->input('email');
        $employee->age = $request->input('age');
        $employee->position_id = $request->input('position');

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ELOQUENT
        $employee = Employee::find($id);

        // // Hapus file terkait tidak menggunakan path jika ada
        // if ($employee->encrypted_filename) {
        //     Storage::delete('public/files/'.$employee->encrypted_filename);
        // }

        // Hapus file terkait menggunakan path jika ada
        if ($employee->encrypted_filename) {
            $path = 'files/'.$employee->encrypted_filename;
            Storage::disk('public')->delete($path);
        }

        $employee->delete();

        return redirect()->route('employees.index');
    }


    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if(Storage::exists($encryptedFilename)) {
            return Storage::download($encryptedFilename, $downloadFilename);
        }
    }

}
