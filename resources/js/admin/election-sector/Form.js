import AppForm from '../app-components/Form/AppForm';

Vue.component('election-sector-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                sector:  '' ,
                block_code:  '' ,
                male_vote:  '' ,
                female_vote:  '' ,
                total_vote:  '' ,
                status:  '' ,
                
            }
        }
    }

});