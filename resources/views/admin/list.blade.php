@extends('layout.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin List (Total: {{ $users->total() }})</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <a href="{{ route('admin.add') }}">Add new admin</a>
                    </div>
                </div>
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Search Admin</h3>
                            </div>
                            <!-- form start -->
                            <form action="{{ route('admin.list') }}" method="GET">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ request()->get('name') }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="email">Email address</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ request()->get('email') }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ request()->get('date') }}" >
                                        </div>
                                        <div style="margin-top: 30px">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.list') }}" class="btn btn-success">Reset</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->


                            </form>
                        </div>
                        <!-- /.card -->

                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>



        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('admin._message')
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Admin List </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>{{ date('m-d-Y', strtotime($user->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.edit', $user->id) }}"
                                                        class="btn btn-primary">Edit</a>
                                                    <a href="{{ route('admin.delete', $user->id) }}"
                                                        class="btn btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                            </div>
                            {{ $users->links() }}
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
