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
<router-view name="TemplateViewComponent"></router-view>
<router-view name="CrontabViewComponent"></router-view>
@endsection
@section('template')
<router-view name="TemplateComponent"></router-view>
<router-view name="TemplateBackComponent"></router-view>
@endsection
@section('crontab')
<router-view name="CrontabComponent"></router-view>
<router-view name="CrontabBackComponent"></router-view>
@endsection