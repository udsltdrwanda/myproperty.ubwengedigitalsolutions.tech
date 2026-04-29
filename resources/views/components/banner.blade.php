<div class="top-0 px-4 mt-5 position-fixed start-0 d-flex justify-content-end w-100" style="z-index: 1050;">
    <!-- Success Message -->
    @if ($message = Session::get('success'))
        <div id="success-message" class="relative p-4 mt-2 text-green-700 bg-green-100 border border-green-400 rounded shadow-lg alert alert-success">
            {{ $message ?? '' }}
            <div class="absolute bottom-0 left-0 h-1 bg-green-400 time-indicator"></div>
        </div>
    @endif

    <!-- Error Message -->
    @if ($message = Session::get('error'))
        <div id="error-message" class="relative p-4 mt-2 text-red-700 bg-red-100 border border-red-400 rounded shadow-lg alert alert-danger">
            {{ $message ?? '' }}
            <div class="absolute bottom-0 left-0 h-1 bg-red-400 time-indicator"></div>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div id="validation-errors" class="relative p-4 mt-2 text-red-700 bg-red-100 border border-red-400 rounded shadow-lg alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <div class="absolute bottom-0 left-0 h-1 bg-red-400 time-indicator"></div>
        </div>
    @endif
</div>

<style>
    .time-indicator {
        animation: increase-width 5s linear forwards;
    }

    @keyframes increase-width {
        from {
            width: 0;
        }
        to {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        setTimeout(() => {
            let successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }

            let errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }

            let validationErrors = document.getElementById('validation-errors');
            if (validationErrors) {
                validationErrors.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    });
</script>
