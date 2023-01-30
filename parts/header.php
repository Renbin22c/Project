<!DOCTYPE html>
<html>
  <head>
    <title>Project</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.25)),
          url(../images/background.jpg);
      }
      .eye {
        display: flex;
        justify-content: space-between;
      }
      .card {
        position: relative;
        overflow: hidden;
        background-color: black;
      }
      .caption{
        position: absolute;
        font-size:x-large;
        left: 0;
        bottom: -10%;
        opacity: 0;
      }
      .more{
        font-size: large;
      }
      .add{
        position: absolute;
        top: 0.5%;
        right: 2%;
        opacity: 0;
      }
      .card img{
        width:  100%;
        height: 100%;
        filter: brightness(90%);
        object-fit: cover;
      }
      .card:hover img{
        transform: scale(1.05);
        transition: 0.8s;
      }
      .card:hover .caption{
        opacity: 1;
        transform: translateY(-25px);
        transition: 0.8s;
      }
      .card:hover .add{
        opacity: 1;
        transition: 0.8s;
      }
    </style>
  </head>
  <body>