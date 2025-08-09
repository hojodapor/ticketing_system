<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Support Form</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 520px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #222;
            font-weight: 700;
            letter-spacing: 1px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
            font-size: 15px;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 25px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 400;
            color: #333;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            border-color: #007BFF;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        button {
            background: linear-gradient(45deg, #007BFF, #0056b3);
            color: white;
            border: none;
            padding: 14px 0;
            font-size: 17px;
            cursor: pointer;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
            box-shadow: 0 6px 18px rgba(0, 86, 179, 0.6);
        }

        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Support Request Form</h2>
        <form action="{{route('ticket.process')}}" method="post" id="ticketForm">
            @csrf
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Your email" required />

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Subject" required />

            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Type your message here..." required></textarea>

            <button type="submit">Submit Request</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Show welcome alert
        document.addEventListener('DOMContentLoaded', function() {
            toastr.success('Welcome to our support portal!', 'Hello!');
        });


        // Form submission alert
document.getElementById('ticketForm').addEventListener('submit', function(e) {
    e.preventDefault();
    toastr.info('Processing your request...', 'Please wait');

    setTimeout(() => {
        toastr.success('Your request was submitted successfully!', 'Success');
        
        setTimeout(() => {
            this.submit(); // Submits after showing success
        }, 1000); // Delay allows the user to see the success message
    }, 1000); // Simulate processing delay
});




    </script>

</body>
</html>
