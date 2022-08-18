import AppForm from '../app-components/Form/AppForm';

Vue.component('visiting-card-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                user_id: '',
                name: '',
                phone: '',
                address: '',
                latlng: '',
                category: '',
                status: '',
                meta: '',
                user_type: '',

            }
        }
    }

});
