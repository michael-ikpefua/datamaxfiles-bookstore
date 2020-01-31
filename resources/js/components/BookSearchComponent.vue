<template>
    <div class="card">
        <div class="card-header">Search For Book</div>

        <div class="card-body">
            <form @submit.prevent="fetchBookFromApi" class="">
                <div class="form-group">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter Name of Book"
                        v-model="nameOfBook"
                    >
                </div>
                <div class="form-group">
                    <button class="btn btn-info" type="submit">Search</button>
                </div>

            </form>

            <div v-if="!bookDetails.length" >
                <ul v-if="status">
                    <li>
                        <span>status_code </span>
                        <span>{{ status}} </span>
                    </li>
                    <li v-if="status === 200">
                        <span>status</span>
                        <span class="badge badge-success">success</span>
                    </li>
                    <li>
                        <span class="font-weight-bold">data</span>
                        <span>[]</span>
                    </li>
                </ul>
            </div>
            <div v-if="bookDetails.length">
                <h3>Book Info</h3>

                <ul class="list-unstyled">
                    <li>
                        <span>status_code </span>
                        <span>{{ status}} </span>
                    </li>
                    <li v-if="status === 200">
                        <span>status</span>
                        <span class="badge badge-success">success</span>
                    </li>

                    <template v-for="(bookDetail) in bookDetails">
                        <li>
                            <span class="font-weight-bold">Name:</span>
                            <span>
                                {{ bookDetail['name'] }}
                            </span>
                        </li>
                        <li>
                            <span class="font-weight-bold">isbn:</span>
                            <span>{{ bookDetail['isbn'] }}</span>
                        </li>
                        <li>
                            <span class="font-weight-bold">authors:</span>
                            <span>{{ bookDetail['authors'] }}</span>
                        </li>
                        <li>
                            <span class="font-weight-bold">number_of_pages:</span>
                            <span>{{ bookDetail['numberOfPages'] }}</span>
                        </li>
                        <li>
                            <span class="font-weight-bold">publisher:</span>
                            <span>{{ bookDetail['publisher'] }}</span>
                        </li>
                        <li>
                            <span class="font-weight-bold">country:</span>
                            <span>{{ bookDetail['country'] }}</span>
                        </li>
                        <li>
                            <span class="font-weight-bold">release_date:</span>
                            <span>{{ bookDetail['released'] | formatDate }}</span>
                        </li>
                    </template>

                </ul>
            </div>



        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        name: "BookSearchComponent",
        data() {
            return {
                nameOfBook: null,
                bookDetails: [],
                status:null
            }
        },

        filters: {
           formatDate(value) {
               if(value) {
                   return moment(String(value)).format('YYYY-MM-DD')
               }
           }
        },

        methods: {
            fetchBookFromApi(){
                axios.get(`https://www.anapioficeandfire.com/api/books?name=${this.nameOfBook}`)
                .then((response) => {
                    this.bookDetails = response.data
                    this.status = response.status
                    this.nameOfBook = null
                })
                .catch((error) => {
                    console.log("WHoopps!")
                })
            }
        }
    }
</script>

<style scoped>

</style>
