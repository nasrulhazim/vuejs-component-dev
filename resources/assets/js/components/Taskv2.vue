<template>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in response.data">
                    <td>{{ task.title }}</td>
                    <td>{{ task.description }}</td>
                    <td>{{ task.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
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