<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

@if (Route::currentRouteName() === 'login')
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
@endif

@if (Route::currentRouteName() === 'login.registro')
    <script src="{{ asset('assets/js/custom/authentication/sign-up/general.js') }}"></script>
@endif

@if (Route::currentRouteName() === 'password.reset')
    <script src="{{ asset('assets/js/custom/authentication/reset-password/new-password.js') }}"></script>
    <script src="{{ asset('assets/js/custom/authentication/reset-password/reset-password.js') }}"></script>
@endif


{{-- <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/new-target.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
<script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script> --}}



{{-- <script>
    $('.dropdown').on('show.bs.dropdown', function() {
        $('body').append($('.dropdown').css({
            position: 'absolute',
            left: $('.dropdown').offset().left,
            top: $('.dropdown').offset().top
        }).detach());
    });
</script> --}}
