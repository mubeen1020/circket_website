@extends('default')
@section('content')
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    
    h1 {
      color: #333333;
      font-size: 32px;
      margin-bottom: 16px;
    }
    
    p {
      color: #666666;
      font-size: 18px;
    }
    
    .loader {
      border: 16px solid #f3f3f3;
      border-top: 16px solid #3498db;
      border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 2s linear infinite;
      margin-bottom: 16px;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
  <div class="container">
    <div class="loader" style="animation-duration: 3s;"></div>
    <h1 style="opacity: 0;">In Progress</h1>
    <p style="opacity: 0;">Please wait while we're working on it.</p>
  </div>

  <script>
    // JavaScript code to apply effects
    const loader = document.querySelector('.loader');
    const heading = document.querySelector('h1');
    const message = document.querySelector('p');

    setTimeout(() => {
      loader.style.animationDuration = '0.5s';
      heading.style.opacity = '1';
      message.style.opacity = '1';
    }, 3000);
  </script>

@stop