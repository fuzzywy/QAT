import zhLocale from 'element-ui/lib/locale/lang/zh-CN'
const cn = {
    messages: {
    	kget: {
    		'KGET': '参数',
	        'QUERY': '参数查询',
	        'CHECK': '参数检查'
    	},
    	task: {
            'fileList':'文件列表',
            'dirPrompt':'请输入正确的目录',
            'inputErrorMessage':'目录格式不正确',
            'Cancelled':'已取消',
            'deleteDirTip':'此操作将永久删除该目录',
    		'TASK': '任务管理',
    		'Log': '数据上传',
            'fileLimitTip':'当前限制选择 10 个文件',
    		'Storage': '入库管理',
            'directory': '目录结构',
            'taskTypeTip':'请先选择入库类型',
            'add': '新建',
            'addSucc':'新增成功',
            'existed':'已存在',
            'notExist':'不存在',
            'delete': '删除',
            'upload': '上传',
            'uploadSucc':'上传成功',
            'StorageType': '入库类型',
            'cancel':'取消',
            'ok': '确定',
            'run': '执行',
            'stop': '停止',
            'filter': '请输入关键字',
            'clear':'清空',
            'taskName':'任务名称',
            'taskInfo':'任务信息',
            'taskNamePlaceholder': '只能包含数字，字母，$和_',
            'taskNameTip':'请先输入任务名称',
            'taskNameExists':'任务名称已存在',
            'storageDirTip':'请选择目录',
            'saveTaskSuccTip':'任务添加成功',
            'saveTaskFailTip':'任务添加失败',
            'deleteWarning':'不能删除一个正在运行的任务',
            'deleteConfirm':'确定删除任务',
            'Undelete':'已取消删除',
            'deleteSuccess':'删除成功',
            'deleteFailed':'删除失败',
            'taskTip':'请先选择任务',
            'tip':'提示',
            'runTip':'不能启动一个正在执行或者执行完毕的任务',
            'stopTip':'只能停止正在运行的任务',
            'TaskDirTip':'无数据，请先进行数据上传',
            'isGZ':'上传文件只能是 gz 格式!',
            'isLt':'上传文件大小不能超过 10MB!'


    	}

    },
    ...zhLocale
}

export default cn