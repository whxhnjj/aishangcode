
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {
    hostName: core.baseUrl,
    realname: '',
    avatarurl: '',
    work_state:1,
    member_rule:0,
  },

  onLoad: function () {
    let that = this;
    that.setData({
      member_rule: app.globalData.member_rule
    });
  },

  
  onShow: function () {
    let that = this;
    that.getMemberInfo();
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    app.getMemberInfo(function (res) {
      that.setData({
        member: res.data.member,  
      });

      if (res.data.member.avatarurl == ''){
        that.setData({
          avatarurl: core.baseUrl + 'static/images/member.png',
          realname: '点击获取头像',
        });
      }else{
        that.setData({
          avatarurl: res.data.member.avatarurl,
          realname: res.data.member.realname,
        });
      }

      if (res.data.member.rule!=0){
        that.setData({
          work_state:res.data.member.state
        });
      }

    })
  },

  //更新用户信息
  onGotUserInfo: function (e) {
    let that = this;
    core.request({
      url: 'api/complete/update',
      data: {
        avatarurl: e.detail.userInfo.avatarUrl,
        nickname: e.detail.userInfo.nickName
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          avatarurl: res.data.member.avatarurl,
          realname: res.data.member.realname
        })
      },
      fail: function (res) {
      }
    });
  },

  //收纸员上下班切换
  workChange:function(){
    let that = this;
    core.request({
      url: 'api/service/switch',
      method: 'GET',
      success: function (res) {
        wx.showToast({
          title: res.msg,
        })
        that.setData({work_state:that.data.work_state == 1?2:1});
      },
      fail: function (res) {

      }
    });
  },

  //用户请求积分信息
  getScore:function(){
    let that = this;
    core.request({
      url: 'api/service/switch',
      method: 'GET',
      success: function (res) {
        that.setData({ work_state: that.data.work_state ? 0 : 1 });
      },
      fail: function (res) {

      }
    });
  },

  //formid
  formidSubmit: function (e) {
    let that = this;
    console.log(e.detail.formId)
    if (e.detail.formId && e.detail.formId != 'the formId is a mock one'){
      
      core.request({
        url: 'api/complete/formid',
        method: 'POST',
        data: {
          formid: e.detail.formId
        },
        success: function (res) {
        },
        fail: function (res) {
        }
      });
    }
  }

})