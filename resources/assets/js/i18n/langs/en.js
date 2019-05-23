import enLocale from 'element-ui/lib/locale/lang/en'
const en = {
    messages: {
    	kget: {
    		'KGET': 'PARAMETER',
        	'QUERY': 'QUERY',
        	'CHECK': 'CHECK'
    	},
    	task: {
            'fileList':'fileList',
            'dirPrompt':'Please enter the correct directory',
            'inputErrorMessage':'Directory format is incorrect',
            'Cancelled':'Cancelled',
            'deleteDirTip':'This action will permanently delete the directory',
    		'TASK': 'TASK',
    		'Log': 'Log',
            'fileLimitTip':'Current limit selection 10 files',
    		'Storage': 'Storage',
            'directory': 'directory',
            'taskTypeTip':'Please select the type of storage first',
            'add': 'add',
            'addSucc':'added successfully',
            'existed':'existed',
            'notExist':'does not exist',
            'delete': 'delete',
            'upload': 'upload',
            'uploadSucc':'Upload success',
            'StorageType': 'StorageType',
            'cancel':'cancel',
            'ok':'ok',
            'run':'run',
            'stop':'stop',
            'filter':'Please enter key words',
            'clear':'Clear',
            'taskName':'taskName',
            'taskInfo':'taskInformation',
            'taskNamePlaceholder':'Can only contain numbers, letters, $ and _',
            'taskNameTip':'Please enter the task name first',
            'taskNameExists':'Task name already exists',
            'storageDirTip':'Please select a directory',
            'saveTaskSuccTip':'Task added successfully',
            'saveTaskFailTip':'Task addition failed',
            'deleteWarning':'Cannot delete a ongoing task',
            'deleteConfirm':'Determine delete task',
            'Undelete':'Undelete',
            'deleteSuccess':'delete successfully',
            'deleteFailed':'delete failed',
            'taskTip':'Please select a task first',
            'tip':'Tip',
            'runTip':'Cannot start a task that is ongoing or complete',
            'stopTip':'Can only stop ongoing task',
            'TaskDirTip':'No log data, please upload log first',
            'isGZ':'Upload files can only be in gz format!',
            'isLt':'The upload file size cannot exceed 10MB!'



    	}
        
    },
    ...enLocale
}

export default en