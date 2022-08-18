import AppForm from '../app-components/Form/AppForm';

Vue.component('parchi-image-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                user_id:  '' ,
                Party:  '' ,
                image_url:  '' ,
                status:  '' ,
                
            }
        }
    }

});