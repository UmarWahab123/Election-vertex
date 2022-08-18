import AppForm from '../app-components/Form/AppForm';

Vue.component('s3uploading-member-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                email:  '' ,
                password:  '' ,
                phone:  '' ,
                party:  '' ,
                last_login:  '' ,
                ip_address:  '' ,
                is_loggedin:  '' ,
                status:  '' ,
                
            }
        }
    }

});