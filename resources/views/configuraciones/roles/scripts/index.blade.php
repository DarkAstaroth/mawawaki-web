<script>
    $(document).ready(function() {
        obtenerRoles();

        // Función para obtener los registros y actualizar la tabla
        function obtenerRoles() {
            $.ajax({
                url: "{{ route('roles.index') }}",
                type: 'GET',
                success: function(response) {
                    actualizarTabla(response);
                },
                error: function(xhr) {}
            });
        }

        function actualizarTabla(registros) {
            // Actualiza la tabla con los registros obtenidos
            // ...
        }
    });
</script>
