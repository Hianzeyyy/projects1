@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Library Catalog</h4>
                </div>
                <div class="card-body">
                    <p class="lead">This is the Stealth Exit (Library Catalog) page. Customize this page with your library resources or catalog system.</p>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> No resources available yet.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
