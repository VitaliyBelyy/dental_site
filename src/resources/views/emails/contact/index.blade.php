@extends('emails.layouts.default')

@section('content')
  <section class="mail">
		<div class="container">
			<div class="row justify-content-center py-5">
				<div class="col-12 mail__heading text-center">
					<h1>Dental CRM</h1>
					<p class="small mb-0">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore  et dolore magna aliqua.
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="text-muted pt-3 mail__field">
						<h4>Имя</h4>
						<p class="pb-1 mb-0 lh-125 border-bottom border-gray">
							{{$name}}
						</p>
					</div>
					@if (isset($email))
						<div class="text-muted pt-3 mail__field">
							<h4>E-mail</h4>
							<p class="pb-1 mb-0 lh-125 border-bottom border-gray">
								{{$email}}
							</p>
						</div>
					@endif
					<div class="text-muted pt-3 mail__field">
						<h4>Телефон</h4>
						<p class="pb-1 mb-0 lh-125 border-bottom border-gray">
							{{$phone}}
						</p>
					</div>
        </div>
        <div class="col-sm-6">
					<div class="text-muted pt-3 mail__field">
						<h4>Сообщение</h4>
						<p class="mb-0 lh-125">
							{{$letterMessage}}
						</p>
					</div>
				</div>
			</div>
		</div>
  </section>
@endsection