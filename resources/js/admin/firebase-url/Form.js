import AppForm from '../app-components/Form/AppForm';

Vue.component('firebase-url-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                image_url:  '' ,
                status:  '' ,
                cron:  '' ,
                
            }
        }
    }

});