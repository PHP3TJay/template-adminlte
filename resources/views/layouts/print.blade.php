@extends('layouts.app')

@section('content')
<style>
    table, th, td {
      padding: 5px !important;/* Adjust the padding as needed */
      border: 1px solid #000 !important;
    }
    table {
        width: 100%;
    }
    
</style>
<div class="wrapper container">
    <section class="invoice ">
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    <img src="{{ asset("assets/images/prosync-logo.png") }}" height="120px" width="250px">
                </h2>
            </div>
        </div>
        
        <div class="row ">
            <h3 class="col-lg-12 text-center" style="background:#000; color: #fefefe; print-color-adjust: exact; ">Coaching Log</h3>
        </div>
        <br><br>
        <div class="">
            <table >
                <tbody>
                    <tr>
                        <td class="col-lg-4 " ><span class="float-right"><b>NAME OF EMPLOYEE</b></span> </td>
                        <td class="col-lg-8" > {{ $agent_name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold col-lg-4 " ><span class="float-right"><b>Designation</b></span></td>
                        <td class="col-lg-8"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div >
            <table>
                <tbody>
                    <tr>
                        <td class="fw-bold col-lg-4" > <span class="float-right"><b>IMMEDIATE SUPERVISOR</b></span></td>
                        <td class="col-lg-8"> {{ $coach_name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold col-lg-4"> <span class="float-right"><b>Designation</b></span></td>
                        <td class="col-lg-8"> </td>
                    </tr>
                </tbody>
            </table>
        </div><br>
        <div >
            <table>
                <tbody>
                    <tr>
                        <td class="fw-bold col-lg-4" > <span class="float-right"><b>DATE OF COACHING</b></span></td>
                        <td class="col-lg-8">  {{ \Carbon\Carbon::parse($coachingLogDetail->date_coached)->format('F d, Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div >
            <table >
                <tbody>
                    <tr>
                        <td class="fw-bold col-lg-4 text-center align-middle">
                            <br>
                            <span>
                              <b>GOAL</b>
                              <br>Purpose of Discussion
                              <br>Desired outcome of the goal
                              <br>Set importance and benefits
                            </span>
                            <br>
                            <br>
                        </td>
                        <td class="col-lg-8"> {!! html_entity_decode($coachingLogDetail->goal) !!}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold col-lg-4 text-center align-middle">
                            <br>
                            <span>
                              <b>REALITY</b>
                              <br>Clarify and agree with facts
                              <br>Difference of goal and reality
                              <br>Determine root causes
                            </span>
                            <br>
                            <br>
                        </td>
                        <td class="col-lg-8"> {!! html_entity_decode($coachingLogDetail->reality) !!}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold col-lg-4 text-center align-middle">
                            <br>
                            <span>
                              <b>OPTIONS</b>
                              <br>Ideas and options for improvement
                              <br>Needed resources and support
                              <br>Action plan and contingencies
                              <br>Confirm progress measurement
                            </span>
                            <br>
                            <br>
                        </td>
                        <td class="col-lg-8"> {!! html_entity_decode($coachingLogDetail->option) !!} </td>
                    </tr>
                    <tr>
                        <td class="fw-bold col-lg-4 text-center align-middle">
                            <br>
                            <span>
                              <b>WILL</b>
                              <br>Recap and highlights
                              <br>Confirm confidence and commitment
                              <br>Next review date
                            </span>
                            <br>
                            <br>
                        </td>
                        <td class="col-lg-8">{!! html_entity_decode($coachingLogDetail->will) !!}</td>
                    </tr>
                </tbody>
            </table>
        </div><br><br>
        <div class="row">
            <div class="col-4">
                <div class="form-group text-center">
                    <span style="display: inline-block; border-bottom: 1px solid #000; width: 100%;">{{ $agent_name }}</span><br>
                    <span>Name and Signature of Employee</span>
                </div>
            </div>
            <div class="col-4"></div>
            <div class="col-4" style="float-right">
                <div class="form-group text-center">
                    <span style="display: inline-block; border-bottom: 1px solid #000; width: 100%;">{{ date('F d, Y') }}</span><br>
                    <span>Date</span>
                </div>
            </div>
        </div>
    </section>
    
</div>
<script>
    window.addEventListener("load", window.print());
</script>
@endsection