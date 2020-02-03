<template>
    <button
        class="btn
        btn-danger btn-sm"
        type="button"
        @click="deleteBook"
    >
        X
    </button>
</template>

<script>
    export default {
        name: "BookDestroyComponent",
        props: {
            bookId : {
                required : true
            }
        },

        methods : {
            deleteBook() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        axios.delete(`/api/v1/books/${this.bookId}`)
                        .then((response) => {

                            Toast.fire({
                                icon : 'success',
                                title : 'Book Deleted Successfully.'
                            })

                            setTimeout(() => {
                                window.location.href = "/books"
                            }, 1000)
                        })
                        .catch((error) => {
                            Swal.fire(
                                'Failed!',
                                'Something Went Wrong.',
                                'warning'
                            )
                        })

                    }
                })
            }

        }

    }
</script>

<style scoped>

</style>
