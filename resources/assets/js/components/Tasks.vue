<template>
	<ul>
	  <li v-for="task in response.data">
	    {{ task.title }}
	  </li>
	</ul>
</template>

<style type="text/css">
	
</style>

<script type="text/javascript">
	export default {
        ready() {
            console.log('Component ready.');
            this.fetch();
        },
        data : function() {
        	return {
        		response : {}
        	}
        },
        methods : {
        	fetch : function() {
        		this.$http.get('/api/tasks/'+Laravel.userId).then((response) => {
        			console.log(response.data);
        			this.response = response.data;
        		}, (response) => {
        			// handle error here
        			alert(response.data.error);
        		});
        	}
        }
    }
</script>