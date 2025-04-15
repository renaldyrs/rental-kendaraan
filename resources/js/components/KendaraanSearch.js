// resources/js/components/KendaraanSearch.js
export default {
    data() {
        return {
            search: '',
            kategori: '',
            status: '',
            kendaraans: [],
            loading: false
        };
    },
    methods: {
        searchKendaraan() {
            this.loading = true;
            
            axios.get('/kendaraan', {
                params: {
                    search: this.search,
                    kategori: this.kategori,
                    status: this.status
                }
            })
            .then(response => {
                this.kendaraans = response.data.data;
            })
            .finally(() => {
                this.loading = false;
            });
        }
    },
    mounted() {
        this.searchKendaraan();
    }
};