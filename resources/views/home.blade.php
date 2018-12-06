@extends('layouts.app')
@section('cog')
  <router-view name="BCogForm"></router-view>
  <router-view name="BCogFormBack"></router-view>
@endsection
@section('content')
<!-- <router-view></router-view> -->
<router-view name="QatComponent"></router-view>
<!-- <router-view name="HomeComponent"></router-view> -->
<router-view name="BCogFormTable"></router-view>
<!-- <router-view name="BPieChart"></router-view> -->
@endsection
@section('template')
<router-view name="TempalteComponent"></router-view>
@endsection
@section('crontab')
<router-view name="CrontabComponent"></router-view>
@endsection