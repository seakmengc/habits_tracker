@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
				{!! Form::open(['route' => 'habits.store', 'method' => 'post']) !!}

					<div class="form-group">
						{!! Form::label('name', 'Habit Name'); !!}
						{!! Form::text('name', '', ['class' => 'form-control']) !!}
						@error('name')
							<p class="text-danger">{{ $errors->first('name') }}</p>
						@enderror
					</div>

					<div class="form-group">
						{!! Form::label('num_hours', 'Number of hours (per week)'); !!} <br>
						{!! Form::selectRange('num_hours', 1, 24) !!}
						@error('num_hours')
							<p class="text-danger">{{ $errors->first('num_hours') }}</p>
						@enderror
					</div>

					<button type="submit" class="btn btn-primary">Create</button>
				{!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
	
@endsection
