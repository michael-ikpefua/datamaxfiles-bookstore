<template>
    <div>
        <button type="button"
                class="btn btn-info btn-sm"
                @click="openModal"
        >
            Edit
        </button>

        <div class="modal fade"
             :id="`bookUpdateModal-${book.id}`"
             tabindex="-1"
             role="dialog"
             aria-labelledby="bookUpdateModalLabel"
             aria-hidden="true"
        >
            <div class="modal-dialog"
                 role="document"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase  text-bold"
                            id="bookUpdateModalLabel">Update Book Details
                        </h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">

                            <form role="form" @submit.prevent="updateBook">

                                <div class="alert alert-danger" v-if="errors">
                                    {{ this.errors }}
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">BookName</label>
                                        <input type="text"
                                               class="form-control"
                                               id="name"
                                               v-model="form.name"
                                        >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="isbn">ISBN</label>
                                        <input type="text"
                                               class="form-control"
                                               id="isbn"
                                               v-model="form.isbn"
                                        >
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="author_id">Author</label>
                                        <input type="text"
                                               class="form-contro"
                                               id="author_id"
                                               v-model="form.authors"
                                               readonly
                                        >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="country">Country</label>
                                        <input type="text"
                                               class="form-control"
                                               id="country"
                                               v-model="form.country"
                                        >
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="page-number">No. of Page(s)</label>
                                        <input type="number"
                                               class="form-control"
                                               id="page-number"
                                               v-model="form.number_of_pages"
                                               min="1"
                                        >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="publishers">Publishers</label>
                                        <input type="text"
                                               class="form-control"
                                               id="publishers"
                                               v-model="form.publishers"
                                        >
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Update Book
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

        </div>
            </div>
        </div>


    </div>


</template>

<script>
export default {
    name: "BookUpdateComponent",
    props : {
        book: {
            required: true,
        }
    },

    data() {
        return {
            form:{
                name : null,
                isbn : null,
                authors : null,
                country : null,
                number_of_pages : null,
                publishers : null
            },

            errors: null
        }
    },

    methods : {
        openModal() {
            this.modalEl.modal()
        },

        updateBook() {
            axios.patch(`/api/v1/books/${this.form.id}`, {
                name : this.form.name,
                isbn: this.form.isbn,
                authors : this.form.authors,
                country : this.form.country,
                number_of_pages : this.form.number_of_pages,
                publishers: this.form.publishers

            }).then((response) => {

                $(`#bookUpdateModal-${this.book.id}`).hide()

                Toast.fire({
                    icon: 'success',
                    title : 'Book Updated Successfully.'
                })

                setTimeout(() => {
                    window.location.href = "/books"
                },1000)
            }).catch((error) => {
                this.errors = "Ensure no field is empty."
            })
        }
    },

    computed : {
        modalEl() {
            return  $(`#bookUpdateModal-${this.book.id}`)
        }
    },

    created() {
        this.form = this.book
    },



}
</script>

<style scoped>

</style>
