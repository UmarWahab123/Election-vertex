import AppForm from '../app-components/Form/AppForm';

Vue.component('candidate-ward-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                user_id:  '' ,
                ward_id:  '' ,
                status:  '' ,
                
            }
        }
    }

});