@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Habits</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Hours (per week)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <ul class="list-group">
                                @forelse ($habits as $key => $habit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <th>{{ $habit->name }}</th>
                                        <td>{{ $habit->num_hours }}</td>
                                        <td>
                                            <form action="{{ route('habits.index') }}" method="post">
                                                <button type="submit" class="btn btn-primary">-1</button>
                                                @csrf
                                            </form>
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text">No Habit Found. Add new to track your habits.</td>
                                    </tr>
                                @endforelse
                            </ul>
                        </tbody>
                    </table>

                    <br>
                    {!! Form::open(['route' => 'habits.create', 'method' => 'get']) !!}
                        <button type="submit" class="btn btn-primary">Add</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
