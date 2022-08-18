import AppForm from '../app-components/Form/AppForm';

Vue.component('url-upload-log-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                user_id:  '' ,
                files_count:  '' ,
                url_meta:  '' ,
                
            }
        }
    }

});