export default {
    methods: {
        logAction(action, modelType, modelId = null, description = null) {
            axios.post('/admin/operation-logs/log-action', {
                action: action,
                model_type: modelType,
                model_id: modelId,
                description: description || `Attempted ${action} action`
            }).catch(error => {
                console.error('Logging failed', error);
            });
        }
    }
}
