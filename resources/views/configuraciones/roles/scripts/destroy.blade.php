<script>
    $(document).ready(function() {
        $('.eliminar-rol').click(function() {
            var id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡Esta acción eliminará el rol!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarRol(id);
                }
            });
        });

        function eliminarRol(id) {
            $.ajax({
                url: "{{ route('roles.destroy', ['role' => ':id']) }}".replace(':id', id),
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire('Eliminado', 'El rol se eliminó correctamente', 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error', 'No se pudo eliminar el rol', 'error');
                }
            });
        }
    });
</script>
