<?php
namespace Common\Model;

use Common\Model\CommonModel;

class UsersModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('user_login', 'require', '用户名称不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
		array('user_pass', 'require', '密码不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT ),
		array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('user_pass', 'require', '密码不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('user_login','','用户名已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
	    array('mobile','','手机号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证mobile字段是否唯一
		array('user_email','require','邮箱不能为空！',0,'regex',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		array('user_email','','邮箱帐号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_email字段是否唯一
		array('user_email','email','邮箱格式不正确！',0,'',CommonModel:: MODEL_BOTH ), // 验证user_email字段格式是否正确
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    array('birthday','',CommonModel::MODEL_UPDATE,'ignore')
	);


    /**
     * @param $sql 条件
     * @return array
     */
    public function search($sql)
    {
        $user = M("users");
        $data = $user->where($sql)->find();
        return $data;
    }

    /**
     * @param $sql 条件
     * @return array
     */
    public function lists($sql, $where)
    {
        $user = M("users");
        $count = $user->where($sql)->count();
        $Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        // //分页跳转的时候保证查询条件
        if (!empty($where)){
            foreach($where as $key=>$val) {
                $Page->parameter[$key]   =   urlencode($val);
            }
        }
        $show    = $Page->show();// 分页显示输出
        $data = $user->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
        $result  = array('data' => $data, 'page' => $show);
        return $result;
    }

    /**
     * @param $id 条件
     * @return array
     */
    public function info($id)
    {
        $user    = M("users");
        $stu_obj = M("stu");
        $data    = $user->where("id = %d", $id)->find();
        $stu     = $stu_obj->where("user_id = %d", $id)->find();
        $arr     = array('data' => $data, 'stu' => $stu);
        return $arr;
    }

	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
		}
	}

	public static function getUsers($where = []){
	    $user = M('users');
        $sql = '1=1';
        if (!empty($where['user_type']) && $where['user_type'] == 'teacher') {
           $sql .= " and (user_type=3 or user_type=4)";
        }
        return $user->where($sql)->select();
    }

    /**
     * 变更账号状态
     */
    public function status($id, $status)
    {
        $user = M("users");
        $data = array('user_status' => $status);
        $res  = $user->where(['id' => $id])->save($data);
        return $res;
    }
}
