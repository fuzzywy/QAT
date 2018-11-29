@extends('layouts.app')
@section('cog')
  <router-view name="BCogForm"></router-view>
  <router-view name="BCogFormBack"></router-view>
@stop
@section('content')
<!-- <router-view></router-view> -->
<router-view name="QatComponent"></router-view>
<!-- <router-view name="HomeComponent"></router-view> -->
<router-view name="BCogFormTable"></router-view>
<!-- <router-view name="BPieChart"></router-view> -->
@endsection
