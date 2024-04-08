<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}">

<style>
    .overlay {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 2000 !important;
    }

    .loader-container {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2000 !important;
    }

    .circle-loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* .select2-container--open {
        z-index: 1055 !important;
    }

    .modal {
        z-index: 1053 !important; 
        overflow: visible !important;
    }

    .modal-backdrop.show {
        z-index: 1040 !important;
    } */
    
    .select2-container--bootstrap4 .select2-search input {
        z-index: 1060 !important;
    } 

    .select2-container .select2-selection--single {
        height: auto; 
    }
    

    #user-table {
        height: 100%;
        overflow-y: auto;
    }

    .login-box {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        /* width: 100%; */
    }

    .login-div {
        min-width: 100%;
    }

    @media print {
        * {
            font-size: 102%;
        }
        h3 {
            -webkit-print-color-adjust: exact !important;
        }
        .col-lg-4 {
            width: 40% !important; 
            float: none !important;
        }
        .col-lg-8 {
            width: 60% !important;
            float: none !important; 
        }

        .text-center {
            text-align: center !important;
        }
        
        .align-middle {
            vertical-align: middle !important;
        }
        table {
            border:solid 3px #000 !important;
            border-width:1px 0 0 1px !important;
        }
        th, td {
            border:solid 3px #000 !important;
            border-width:0 1px 1px 0 !important;
        }
        .form-group {
            display: inline-block !important;
        }
        
    }


    .my-swalert-input {
        min-width: 600px; 
    }


    

</style>

