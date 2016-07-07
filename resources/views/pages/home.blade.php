@extends('layouts.app')

@section('htmlheader_title')
  Home
@endsection

@section('contentheader_description')

@endsection

@section('contentheader_title')
  Welcome, {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}
@endsection

@section('breadcrumb')
  <li><a href="{{ action('HomeController@index') }}"><i class="fa fa-circle-o-notch"></i> Home</a></li>
  <li class="active">Here</li>
@endsection

@section('main-content')
  <div class="container spark-screen">
    <div class="row">
      <div class="col-md-12">
        @include('layouts.partials.tabcontent', ['activeTab' => count($errors) > 0 ? 'timesheet' : 'timeline'])
      </div>
    </div>
  </div>
@endsection
