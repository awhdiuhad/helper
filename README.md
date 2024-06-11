# 封装一些经常用的函数
1. check_captcha($phone, $code, $msg) 校验验证码是否正确，万能验证码888888

| 参数    | 含义               |
|-------|------------------|
| phone | 手机号              |
| code  | 验证码              |
| msg   | 错误提示信息，默认是验证码不正确 |

1. format_time($time, $type) 格式化时间

| 参数   | 含义                                       |
|------|------------------------------------------|
| time | 需要格式化的时间                                 |
| type | 1是Y-m-d H:i:s；2是Y-m-d H:i；3是Y-m-d；4是聊天时间 |

1. generate_nickname() 生成昵称
2. handle_get_pic($pics, $type) 生成可直接访问的地址

| 参数    | 含义                                 |
|-------|------------------------------------|
| $pics | 图片，多张使用逗号分隔                        |
| type  | 1是单张需要默认；2是多张；3是单张不需要默认；4多张中取一张有默认 |

1. handle_set_pic($pics, $type = 1) 生成不带域名的地址

| 参数    | 含义          |
|-------|-------------|
| $pics | 图片，多张使用逗号分隔 |
| type  | 1是单张；2是多张   |

1. handle_set_content($content) 生成不带域名的富文本

| 参数      | 含义    |
|---------|-------|
| content | 富文本内容 |

1. handle_get_content($content = null) 生成带域名的富文本

| 参数      | 含义    |
|---------|-------|
| content | 富文本内容 |

1. handle_get_num($num = null) 生成带w的数字

| 参数  | 含义 |
|-----|----|
| num | 数字 |

1. generate_room_num($fromId, $toId) 生成聊天房间号

| 参数     | 含义    |
|--------|-------|
| fromID | 发送者ID |
| toID   | 接受者ID |

1. result($data = null, $code, $msg = '操作成功', $url = null)