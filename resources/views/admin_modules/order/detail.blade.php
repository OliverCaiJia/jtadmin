<div class="bjui-pageContent">
    <div class="bjui-pageContent">
        <div style="margin:15px auto 0;">
            <legend>详情</legend>
            <!-- Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#home" role="tab" data-toggle="tab">
                        申请信息
                    </a>
                </li>
                <li>
                    <a href="#carrier" role="tab" data-toggle="tab">
                        运营商
                    </a>
                </li>
                <li>
                    <a href="#address-book" role="tab" data-toggle="tab">
                        通讯录
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade active in" id="home">
                    渠道：{{ \App\Models\Factory\Saas\Channel\ChannelFactory::getNameById($order->channel_id) }} 申请金额：{{ $order->amount }} 周期： {{ $order->cycle }}天 还款方式：
                    @if($order->repayment_method == 1)
                        一次还
                    @elseif($order->repayment_method == 2)
                        分期还
                    @endif
                    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
                        <thead>
                            <tr>
                                <th>姓名</th>
                                <th>手机号</th>
                                <th>性别</th>
                                <th>星座</th>
                                <th>所属省</th>
                                <th>所属市</th>
                                <th>所属县</th>
                                <th>籍贯</th>
                            </tr>
                        </thead>
                        <?php $userBasic = $order->userReport->user_basic;?>
                        <tbody>
                            <tr>
                                <td>{{ $userBasic['name'] }}</td>
                                <td>{{ $userBasic['mobile'] }}</td>
                                <td>{{ $userBasic['gender'] }}</td>
                                <td>{{ $userBasic['constellation'] }}</td>
                                <td>{{ $userBasic['province'] }}</td>
                                <td>{{ $userBasic['city'] }}</td>
                                <td>{{ $userBasic['region'] }}</td>
                                <td>{{ $userBasic['native_place'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="carrier">
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>基本信息</h5>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="control-label">手机号：</label></td>
                                <td>{{ $userBasic['mobile'] }}</td>
                                <td><label class="control-label">姓名：</label></td>
                                <td>{{ $userBasic['name'] }}</td>
                            </tr>
                            <tr>
                                <td><label class="control-label">证件号：</label></td>
                                <td><label class="control-label">{{ $userBasic['id_card'] }}</label></td>
                                <td><label class="control-label">报告获取时间：</label></td>
                                <td>{{ $userBasic['update_time'] }}</td>
                            </tr>
                            <tr>
                                <td><label class="control-label"> 运营商：</label></td>
                                <td>{{ $userBasic['source_name_zh'] }}</td>
                                <td><label class="control-label">帐号状态：</label></td>
                                <td>1-单向停机</td>
                            <tr>
                                <td><label class="control-label">开户时间：</label></td>
                                <td>{{ $userBasic['reg_time'] ?? '' }}</td>
                                <td><label class="control-label">开户时长：</label></td>
                                <td>{{ $userBasic['in_time'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><label class="control-label">用户邮箱：</label></td>
                                <td>{{ $userBasic['email'] ?? '' }}</td>
                                <td><label class="control-label">用户地址：</label></td>
                                <td>{{ $userBasic['address'] ?? ''}}</td>
                            </tr>
                            <tr>
                                <td><label class="control-label">手机号归属地：</label></td>
                                <td>{{ $userBasic['phone_attribution'] }}</td>
                                <td><label class="control-label">居住地址：</label></td>
                                <td>{{ $userBasic['live_address'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><label class="control-label">余额：</label></td>
                                <td>{{ isset($userBasic['available_balance']) ? ($userBasic['available_balance'] / 100) . '元' : ''}}</td>
                                <td><label class="control-label">套餐：</label></td>
                                <td>{{ $userBasic['package_name'] ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{--基本信息校验--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $basicCheck = $order->userReport->basic_check_items;?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <h5>基本信息校验</h5>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">名称</td>
                                <td colspan="2">结果</td>
                                <td colspan="2">说明</td>
                            </tr>
                            <tr>
                                <td colspan="2">身份证有效性</td>
                                <td colspan="2">{{ $basicCheck['idcard_check']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['idcard_check']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">邮箱有效性</td>
                                <td colspan="2">{{ $basicCheck['email_check']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['email_check']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">地址有效性</td>
                                <td colspan="2">{{ $basicCheck['address_check']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['address_check']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">通过话记录完整性</td>
                                <td colspan="2">{{ $basicCheck['call_data_check']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['call_data_check']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">身份证号码是否与运营商数据匹配</td>
                                <td colspan="2">{{ $basicCheck['idcard_match']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['idcard_match']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">姓名是否与运营商数据匹配</td>
                                <td colspan="2">{{ $basicCheck['name_match']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['name_match']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">申请人姓名+身份证号码是否出现在法院黑名单</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_idcard_in_court_black']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_idcard_in_court_black']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">申请人姓名+身份证号码是否出现在金融机构黑名单</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_idcard_in_finance_black']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_idcard_in_finance_black']['comment'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">申请人姓名+手机号码是否出现在金融机构黑名单</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_mobile_in_finance_black']['result'] }}</td>
                                <td colspan="2">{{ $basicCheck['is_name_and_mobile_in_finance_black']['comment'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{--联系人信息核对--}}
                    <?php $basicCheck = $order->userReport->application_check;?>
                    @if($basicCheck)
                        <table class="table table-condensed table-hover" width="100%">
                            <tbody>
                                <tr>
                                    <td colspan="12" class="text-center">
                                        <h5>联系人信息核对</h5>
                                    </td>
                                </tr>
                                @foreach($basicCheck as $item)
                                    <tr>
                                        <td><label class="control-label">姓名：</label></td>
                                        <td>{{ $item['contact_name'] }}</td>
                                        <td><label class="control-label">与申请人关系：</label></td>
                                        <td>{{ $item['relationship'] }}</td>
                                        <td><label class="control-label">手机号：</label></td>
                                        <td>{{ $item['key_value'] }}</td>
                                        <td>{{ $item['check_xiaohao'] }}</td>
                                        <td><label class="control-label">与该联系人通话记录：</label></td>
                                        <td>{{ $item['check_mobile'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    {{--指定联系人联系情况--}}
                    <?php $contacts = $order->userReport->collection_contact;?>
                    @if($contacts)
                        <table class="table table-condensed table-hover" width="100%">
                            <tbody>
                                <tr>
                                    <td colspan="12" class="text-center">
                                        <h5>指定联系人联系情况</h5>
                                    </td>
                                    <tr>
                                        <td colspan="2">序号</td>
                                        <td colspan="2">联系人号码</td>
                                        <td colspan="2">姓名</td>
                                        <td colspan="2">关系</td>
                                        <td colspan="2">归属地</td>
                                        <td colspan="2">通话次数</td>
                                        <td colspan="2">通话时长</td>
                                        <td colspan="2">第一次通话</td>
                                        <td colspan="2">最后一次通话</td>
                                        <td colspan="2">短信次数</td>
                                    </tr>
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td colspan="2">{{ $loop->index + 1 }}</td>
                                            <td colspan="2">{{ $contact['phone_num'] }}</td>
                                            <td colspan="2">{{ $contact['contact_name'] }}</td>
                                            <td colspan="2">{{ $contact['relationship'] }}</td>
                                            <td colspan="2">{{ $contact['phone_num_loc'] }}</td>
                                            <td colspan="2">{{ $contact['call_cnt'] }}</td>
                                            <td colspan="2">{{ $contact['call_time'] }}</td>
                                            <td colspan="2">{{ $contact['trans_start'] }}</td>
                                            <td colspan="2">{{ $contact['trans_end'] }}</td>
                                            <td colspan="2">{{ $contact['sms_cnt'] }}</td>
                                        </tr>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    @endif
                    {{--用户信息检测--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $userInfoCheck = $order->userReport->user_info_check;?>
                            @if($userInfoCheck)
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>用户信息监测</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>直接联系人中黑名单人数：</td>
                                <td>{{ $userInfoCheck['contacts_class1_blacklist_cnt'] }}</td>
                            </tr>
                            <tr>
                                <td>间接联系人中黑名单人数：</td>
                                <td>{{ $userInfoCheck['contacts_class2_blacklist_cnt'] }}</td>
                            </tr>
                            <tr>
                                <td>直接联系人人数：</td>
                                <td>{{ $userInfoCheck['contacts_class1_cnt'] }}</td>
                            </tr>
                            <tr>
                                <td>引起间接黑名单人数：</td>
                                <td>{{ $userInfoCheck['contacts_router_cnt'] }}</td>
                            </tr>
                            <tr>
                                <td>直接联系人中引起间接黑名单占比：</td>
                                <td>{{ $userInfoCheck['contacts_router_ratio'] }}</td>
                            </tr>
                            <tr>
                                <td>查询过该用户的相关企业类型（姓名+身份证号+电话号码）：</td>
                                <td>
                                    @foreach($userInfoCheck['searched_org_type'] as $type)
                                        {{ $type }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>身份证组合过的其他姓名：</td>
                                <td>
                                    @foreach($userInfoCheck['idcard_with_other_names'] as $name)
                                        {{ $name }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>身份证组合过的其他电话：</td>
                                <td>
                                    @foreach($userInfoCheck['idcard_with_other_phones'] as $phone)
                                        {{ $phone }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>电话号码组合过其他姓名：</td>
                                <td>
                                    @foreach($userInfoCheck['phone_with_other_names'] as $name)
                                        {{ $name }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>电话号码组合过其他身份证：</td>
                                <td>
                                    @foreach($userInfoCheck['phone_with_other_idcards'] as $idcard)
                                        {{ $idcard }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>电话号码注册过相关的企业数量：</td>
                                <td>{{ $userInfoCheck['register_org_cnt'] }}</td>
                            </tr>
                            <tr>
                                <td>电话号码注册过相关的企业类型：</td>
                                <td>
                                    @foreach($userInfoCheck['register_org_type'] as $type)
                                        {{ $type }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>电话号码出现过的公开信息网站：</td>
                                <td>
                                    @foreach($userInfoCheck['arised_open_web'] as $web)
                                        {{ $web }} &nbsp;
                                    @endforeach
                                </td>
                            </tr>
                                @endif
                        </tbody>
                    </table>
                    {{--行为分析--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $cellBehaviors = $order->userReport->cell_behavior;?>
                            @if($cellBehaviors)
                            <tr>
                                <td colspan="12" class="text-center">
                                    <h5>行为分析</h5>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">月份</td>
                                <td colspan="2">消费金额(分)</td>
                                <td colspan="2">流量使用</td>
                                <td colspan="2">短信</td>
                                <td colspan="2">充值次数</td>
                                <td colspan="2">充值金额(分)</td>
                                <td colspan="2">通话次数</td>
                                <td colspan="2">通话时长(秒)</td>
                                <td colspan="2">主叫次数</td>
                                <td colspan="2">主叫时长(秒)</td>
                                <td colspan="2">被叫次数</td>
                                <td colspan="2">被叫时长(秒)</td>
                            </tr>
                            @foreach($cellBehaviors as $cellBehavior)
                                <tr>
                                    <td colspan="2">{{ $cellBehavior['cell_mth'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['total_amount'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['net_flow'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['cell_mth'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['rechange_cnt'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['rechange_amount'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['call_cnt'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['call_time'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['dial_cnt'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['dial_time'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['dialed_cnt'] }}</td>
                                    <td colspan="2">{{ $cellBehavior['dialed_time'] }}</td>
                                </tr>
                            @endforeach
                                @endif
                        </tbody>
                    </table>
                    {{--通话详单--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $callContactDetail = $order->userReport->call_contact_detail;?>
                            @if($callContactDetail)
                            <tr>
                                <td colspan="12" class="text-center">
                                    <h5>通话详单</h5>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">序号</td>
                                <td colspan="2">联系人号码</td>
                                <td colspan="2">关系</td>
                                <td colspan="2">归属地</td>
                                <td colspan="2">标识</td>
                                <td colspan="2">近一周通过次数</td>
                                <td colspan="2">近一个月通话次数</td>
                                <td colspan="2">近三个月通话次数(</td>
                                <td colspan="2">近六个月通话次数</td>
                            </tr>
                            @foreach($callContactDetail as $callContact)
                                <tr>
                                    <td colspan="2">{{ $loop->index + 1 }}</td>
                                    <td colspan="2">{{ $callContact['peer_num'] }}</td>
                                    <td colspan="2">{{ $callContact['p_relation'] }}</td>
                                    <td colspan="2">{{ $callContact['city'] }}</td>
                                    <td colspan="2">{{ $callContact['company_name'] }}</td>
                                    <td colspan="2">{{ $callContact['call_cnt_1w'] }}</td>
                                    <td colspan="2">{{ $callContact['call_cnt_1m'] }}</td>
                                    <td colspan="2">{{ $callContact['call_cnt_3m'] }}</td>
                                    <td colspan="2">{{ $callContact['call_cnt_6m'] }}</td>
                                </tr>
                            @endforeach
                                @endif
                        </tbody>
                    </table>
                    {{--联系人区域汇总--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $contactRegion = $order->userReport->contact_region;?>
                            @if($contactRegion)
                            <tr>
                                <td colspan="12" class="text-center">
                                    <h5>联系人区域汇总</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>地区</td>
                                <td>通话号码数</td>
                                <td>通话次数</td>
                                <td>通话时长(秒)</td>
                                <td>主叫次数</td>
                                <td>主叫时长</td>
                                <td>被叫次数</td>
                                <td>被叫时长</td>
                            </tr>
                            @foreach($contactRegion as $value)
                                <tr>
                                    <td>{{ $value['region_loc'] }}</td>
                                    <td>{{ $value['region_uniq_num_cnt'] }}</td>
                                    <td>{{ $value['region_call_cnt'] }}</td>
                                    <td>{{ $value['region_call_time'] }}</td>
                                    <td>{{ $value['region_dial_cnt'] }}</td>
                                    <td>{{ $value['region_dial_time'] }}</td>
                                    <td>{{ $value['region_dialed_cnt'] }}</td>
                                    <td>{{ $value['region_dialed_time'] }}</td>
                                </tr>
                            @endforeach
                                @endif
                        </tbody>
                    </table>
                    {{--亲情号通话详单--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $callFamilyDetail = $order->userReport->call_family_detail;?>
                            <tr>
                                <td colspan="12" class="text-center">
                                    <h5>亲情号通话详单</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>号码</td>
                                <td>是否亲情网用户</td>
                                <td>是否亲情网户主</td>
                                <td>连续充值月数</td>
                                <td>常用联系地址是否手机归属地</td>
                                <td>通话次数</td>
                                <td>费用使用情况</td>
                                <td>本机号码归属地使用情况</td>
                            </tr>
                            <tr>
                                <td>{{ $userBasic['mobile'] }}</td>
                                <td>{{ $callFamilyDetail['is_family_member'] }}</td>
                                <td>{{ $callFamilyDetail['is_family_master'] }}</td>
                                <td>{{ $callFamilyDetail['continue_recharge_month_cnt'] }}</td>
                                <td>{{ $callFamilyDetail['is_address_match_attribution'] }}</td>
                                <td>通话次数 小于 使用月数＊1次 :{{ $callFamilyDetail['is_address_match_attribution'] }}</td>
                                <td>{{ $callFamilyDetail['unpaid_month_cnt'] }}</td>
                                <td>{{ $callFamilyDetail['live_month_cnt'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{--亲情号通话汇总--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>亲情号通话汇总</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>周期</td>
                                <td>近一个月</td>
                                <td>近三个月</td>
                                <td>近六个月</td>
                            </tr>
                            <tr>
                                <td>通话数量</td>
                                <td>9</td>
                                <td>34</td>
                                <td>67</td>
                            </tr>
                        </tbody>
                    </table>
                    {{--行为检测--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $behaviorCheck = $order->userReport->behavior_check;?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>行为检测</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>分析点</td>
                                <td>结果</td>
                                <td>说明</td>
                            </tr>
                            <tr>
                                <td>朋友圈</td>
                                <td>{{ $behaviorCheck['regular_circle']['result'] }}</td>
                                <td>{{ $behaviorCheck['regular_circle']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>号码使用时间</td>
                                <td>{{ $behaviorCheck['phone_used_time']['result'] }}</td>
                                <td>{{ $behaviorCheck['phone_used_time']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>手机静默情况</td>
                                <td>{{ $behaviorCheck['phone_silent']['result'] }}</td>
                                <td>{{ $behaviorCheck['phone_silent']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>关机情况</td>
                                <td>{{ $behaviorCheck['phone_power_off']['result'] }}</td>
                                <td>{{ $behaviorCheck['phone_power_off']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>互通电话的号码数量</td>
                                <td>{{ $behaviorCheck['contact_each_other']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_each_other']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>与澳门地区电话通话情况</td>
                                <td>{{ $behaviorCheck['contact_macao']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_macao']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>与110通话情况</td>
                                <td>{{ $behaviorCheck['contact_110']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_110']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>与120通话情况</td>
                                <td>{{ $behaviorCheck['contact_120']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_120']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>与律师通话情况</td>
                                <td>{{ $behaviorCheck['contact_lawyer']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_lawyer']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>与法院通话情况</td>
                                <td>{{ $behaviorCheck['contact_court']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_court']['evidence'] }}</td>
                            </tr><tr>
                                <td>与贷款类号码联系情况</td>
                                <td>{{ $behaviorCheck['contact_loan']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_loan']['evidence'] }}</td>
                            </tr><tr>
                                <td>与银行类号码联系情况</td>
                                <td>{{ $behaviorCheck['contact_bank']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_bank']['evidence'] }}</td>
                            </tr><tr>
                                <td>与信用卡类号码联系情况</td>
                                <td>{{ $behaviorCheck['contact_credit_card']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_credit_card']['evidence'] }}</td>
                            </tr><tr>
                                <td>与催收类号码联系情况</td>
                                <td>{{ $behaviorCheck['contact_collection']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_collection']['evidence'] }}</td>
                            </tr><tr>
                                <td>夜间活动情况（0点-7点）</td>
                                <td>{{ $behaviorCheck['contact_night']['result'] }}</td>
                                <td>{{ $behaviorCheck['contact_night']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>居住地本地（省份）地址在电商中使用时长</td>
                                <td>{{ $behaviorCheck['dwell_used_time']['result'] }}</td>
                                <td>{{ $behaviorCheck['dwell_used_time']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>总体电商使用情况</td>
                                <td>{{ $behaviorCheck['ebusiness_info']['result'] }}</td>
                                <td>{{ $behaviorCheck['ebusiness_info']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>申请人本人电商使用情况</td>
                                <td>{{ $behaviorCheck['person_ebusiness_info']['result'] }}</td>
                                <td>{{ $behaviorCheck['person_ebusiness_info']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>虚拟商品购买情况</td>
                                <td>{{ $behaviorCheck['virtual_buying']['result'] }}</td>
                                <td>{{ $behaviorCheck['virtual_buying']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>彩票购买情况</td>
                                <td>{{ $behaviorCheck['lottery_buying']['result'] }}</td>
                                <td>{{ $behaviorCheck['lottery_buying']['evidence'] }}</td>
                            </tr>
                            <tr>
                                <td>号码通话情况</td>
                                <td>{{ $behaviorCheck['phone_call']['result'] }}</td>
                                <td>{{ $behaviorCheck['phone_call']['evidence'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{--通话风险分析--}}
                    <table class="table table-condensed table-hover" width="100%">
                        <tbody>
                            <?php $callRiskAnalysis = $order->userReport->call_risk_analysis;?>
                            @if($callRiskAnalysis)
                            <tr>
                                <td colspan="4" class="text-center">
                                    <h5>通话风险分析</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>通话类型</td>
                                <td>分析描述</td>
                            </tr>
                            @foreach($callRiskAnalysis as $key => $analysis)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>
                                        {{--1个月--}}
                                        近1个月通话总次数{{ $analysis['call_cnt_1m'] }}
                                        近1个月通话总时长{{ $analysis['call_cnt_1m'] }}
                                        近1个月主叫通话次数{{ $analysis['call_analysis_dial_point']['call_dial_cnt_1m'] }}
                                        近1个月主叫通话总时长{{ $analysis['call_analysis_dial_point']['call_dial_time_1m'] }}
                                        近1个月被叫通话次数{{ $analysis['call_analysis_dialed_point']['call_dialed_cnt_1m'] }}
                                        近1个月被叫通话总时长{{ $analysis['call_analysis_dialed_point']['call_dialed_time_1m'] }} <br>
                                        {{--3个月--}}
                                        近3个月通话总次数{{ $analysis['call_cnt_3m'] }}
                                        近3个月通话总时长{{ $analysis['call_cnt_3m'] }}
                                        近3个月主叫通话次数{{ $analysis['call_analysis_dial_point']['call_dial_cnt_3m'] }}
                                        近3个月主叫通话总时长{{ $analysis['call_analysis_dial_point']['call_dial_time_3m'] }}
                                        近3个月被叫通话次数{{ $analysis['call_analysis_dialed_point']['call_dialed_cnt_3m'] }}
                                        近3个月被叫通话总时长{{ $analysis['call_analysis_dialed_point']['call_dialed_time_3m'] }} <br>
                                        {{--6个月--}}
                                        近6个月通话总次数{{ $analysis['call_cnt_6m'] }}
                                        近6个月通话总时长{{ $analysis['call_cnt_6m'] }}
                                        近6个月主叫通话次数{{ $analysis['call_analysis_dial_point']['call_dial_cnt_6m'] }}
                                        近6个月主叫通话总时长{{ $analysis['call_analysis_dial_point']['call_dial_time_6m'] }}
                                        近6个月被叫通话次数{{ $analysis['call_analysis_dialed_point']['call_dialed_cnt_6m'] }}
                                        近6个月被叫通话总时长{{ $analysis['call_analysis_dialed_point']['call_dialed_time_6m'] }} <br>
                                    </td>
                                </tr>
                            @endforeach
                                @endif
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="address-book">
                    暂无数据
                </div>
            </div>
        </div>
    </div>
</div>
