
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    state:0,
    mengShow: false,
    aniStyle: true,
    bankShow: false,
    bank_list:[
      {
        id:0,
        name:'中国银行',
        value:'1026'
      },
      {
        id: 1,
        name: '工商银行',
        value: '1002'
      },
      {
        id: 2,
        name: '农业银行',
        value: '1005'
      },
      {
        id: 3,
        name: '邮政银行',
        value: '1066'
      },
      {
        id: 4,
        name: '交通银行',
        value: '1020'
      },
      {
        id: 5,
        name: '建设银行',
        value: '1003'
      },
      {
        id: 6,
        name: '招商银行',
        value: '1001'
      },
      {
        id: 7,
        name: '民生银行',
        value: '1006'
      },
      {
        id: 8,
        name: '中信银行',
        value: '1021'
      },
      {
        id: 9,
        name: '浦发银行',
        value: '1004'
      }
    ],
    bank_list_selected: '',
    true_name: '', //新卡姓名
    bank_no: '', //新卡号
    bank:[], //用户个人银行卡列表
    bank_checked:'', //选中银行卡
    total_money:0, //全部金额
    apply_amount:'' //提现金额
  },


  onLoad: function (options) {
  },

  onShow:function(){
    let that = this;
    that.getOwnBankList();
    that.getMemberInfo();
  },

  //获取用户金额
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        that.setData({
          total_money: res.data.member.cash
        });
      },
      fail: function (res) {
      }
    });
  },

  //全部提现
  allPresent:function(){
    let that = this;
    that.setData({
      apply_amount:that.data.total_money/100
    });
  },

  // 提现
  userPresent:function(e){
    let that = this;
    // console.log(that.data.bank_checked);
    core.request({
      url: 'api/member/withdraw',
      method: 'POST',
      data: {
        bank_id: that.data.bank_checked?that.data.bank_checked.id:'',
        apply_amount: e.detail.value.apply_amount
      },
      success: function (res) {
        wx.showToast({
          title: '提交成功',
        });
        setTimeout(function(){
          wx.redirectTo({
            url: '../agent_present_detail/agent_present_detail?id=' + res.data.id
          });
        },500);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: "none"
        });
      }
    });
  },

  //请求银行卡列表
  getOwnBankList:function(num){
    let that = this;
    core.request({
      url: 'api/member/getbank',
      method: 'GET',
      success: function (res) {
      
       var bank = res.data.bank;
       var bank_checked = '';
       for(let i = 0 ; i < bank.length ; i++){
         bank[i].bank_four = bank[i].bank_no.slice(-4);
       }

       if(num){
         for(let i=0;i<bank.length;i++){
           if(bank[i].id == num){
             bank[i].checked = true;
             bank_checked = bank[i];
           }
         }
       }else{
         if(bank.length>0){
           bank[0].checked = true;
           bank_checked = bank[0];
         }
       }
      
       that.setData({
         bank: bank,
         bank_checked: bank_checked
       });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });
  },

  //选择到账银行
  bankChoose:function(e){
    let that = this;
    var index = e.target.id;
    var bank = that.data.bank;
    for(let i = 0 ; i < bank.length ; i++){
      bank[i].checked = false;
    }
    bank[index].checked = true;
    that.setData({
      bank: bank,
      bank_checked: bank[index]
    });
    that.outbtn();
  },

  //删除银行卡
  deleteBank:function(e){
    let that =this;
    var index = e.target.id;
    wx.showModal({
      title: '提示',
      content: '确定删除此银行卡吗？',
      success:function(res){
        if (res.confirm){
          core.request({
            url: 'api/member/delbank',
            method: 'POST',
            data: {
              id: that.data.bank[index].id,
            },
            success: function (res) {
              wx.showToast({
                title: res.msg,
              });
              setTimeout(function () {
                that.getOwnBankList();
              }, 500);
            }
          });

        }
      }
    })

  },

  //打开新卡界面
  openNewBank:function(){
    let that = this;
    var bank = that.data.bank;
    for (let i = 0; i < bank.length; i++) {
      bank[i].checked = false;
    }
    that.setData({
      state:1,
      bank:bank,
      bank_checked: '',
    });
  },

  //返回界面
  newBankBack:function(){
    let that = this;
    that.getOwnBankList();
    that.setData({
      state: 0,
      new_checked: false
    });
  },

  //添加新银行卡
  newBankCard:function(e){
    let that = this;
    core.request({
      url: 'api/member/bank',
      method: 'POST',
      data: {
        true_name: e.detail.value.true_name,
        bank_name: e.detail.value.bank_name,
        bank_code: e.detail.value.bank_code,
        bank_no: e.detail.value.bank_no
      },
      success: function (res) {
        wx.showToast({
          title: '添加成功',
        });
        that.getOwnBankList(res.data.id);
        setTimeout(function(){
          that.setData({
            state: 0,
            new_checked:false,
            bank_list_selected: '',
            true_name: '',
            bank_no: ''
          });
        },500);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });
  },

  //银行列表选择
  bankSelected: function (e) {
    let that = this;
    var index = e.target.id;
    that.setData({
      bank_list_selected: that.data.bank_list[index],
      bankShow: false
    });
  },

  //弹出层显示
  showPop: function () {
    let that = this;
    that.setData({
      mengShow: true,
      aniStyle: true
    });
  },

  //弹出层隐藏
  outbtn: function () {
    let that = this;
    that.setData({
      aniStyle: false
    })
    setTimeout(function () {
      that.setData({
        mengShow: false
      })
    }, 500)
  },

  //阻止冒泡事件
  inbtn: function (e) {
  },

  //银行列表显示
  bankShow: function () {
    let that = this;
    that.setData({
      bankShow: true,
    });
  },

  //银行列表隐藏
  bankOut: function () {
    let that = this;
    that.setData({
      bankShow: false
    })
  }
})