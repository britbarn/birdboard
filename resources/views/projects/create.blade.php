@extends('layouts.app')

@section('content')
	

	<form method="POST" action="/projects">

		@csrf

		<h1>Create a project</h1>

		<div class="field">

			<label class="label" for="title"></label>

			<div class="control">

				<input type="text" class="input" name="title" placeholder="title">
				
			</div>
			
		</div>

		<div class="field">

			<label class="label" for="description">Description</label>

			<div class="control">

				<textarea name="description" class="textarea"></textarea>
				
			</div>
			
		</div>

		<div class="field">
			
			<div class="control">
				
				<button type="submit" class="button">Create Project</button>

				<a href="/projects">Cancel</a>

			</div>
			
		</div>

	</form>

@endsection