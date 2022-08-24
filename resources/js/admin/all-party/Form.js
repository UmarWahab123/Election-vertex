import AppForm from '../app-components/Form/AppForm';

Vue.component('all-party-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                party_name:  '' ,
                party_image_url:  '' ,
                created_by:  '' ,
                
            }
        }
    }

});