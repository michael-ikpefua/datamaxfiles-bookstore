<template>
    <div class="card">
        <div class="card-header">List of Books</div>

        <div class="card-body">
            <div v-if="!books.data" class="alert alert-danger text-center text-uppercase font-weight-bold">
                no books available
            </div>
            <table class="table" v-else>
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Author</th>
                    <th scope="col">Isbn</th>
                    <th scope="col">Country</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(book) in books.data" :key="book.id">
                    <th >{{ book.name }}</th>
                    <td>{{ book.authors }}</td>
                    <td>{{ book.isbn }}</td>
                    <td>{{ book.country }}</td>
                    <td class="d-inline-flex">
                        <book-update :book="book"></book-update>
                        <book-destroy :book-id="book.id" ></book-destroy>
                    </td>
                </tr>

                </tbody>
            </table>

        </div>

        <div class="card-footer">
            <pagination :data="books"
                        @pagination-change-page="fetchAllBooks">
            </pagination>
        </div>
    </div>
</template>

<script>

    export default {
        name: "BookIndexComponent",

        data() {
            return {
                books : {},
                status : null,
                status_code : null
            }
        },

        methods:{
            fetchAllBooks(page = 1) {
                axios.get('/api/v1/books?page=' + page)
                .then((response) => {
                    this.books = response.data
                    this.status = response.data.status
                    this.status_code = response.data.status_code

                }).catch((error) => {
                    console.log("Whoops. Something went wrong")
                })
            }
        },

        created() {
            this.fetchAllBooks();
        }
    }
</script>

<style scoped>

</style>
