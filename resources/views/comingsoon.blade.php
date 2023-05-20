@extends('default')
@section('content')
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <div class="container" style="  display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;">
    <div  style="animation-duration: 3s; border: 16px solid #f3f3f3;
      border-top: 16px solid #3498db;
      border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 2s linear infinite;
      margin-bottom: 16px;"></div>
 <h1>
    In Progress
 </h1>

  </div>



@stop