
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    hostName: core.baseUrl,
    state: 0, 
    page: 1,
    isReachBottom: false,
    page_text: '加载更多',
    team_member: []
  },

  onLoad: function () {
    let that = this;
    wx.hideShareMenu();
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, team_member: [], isReachBottom: false });
    that.getMemberInfo();
    that.getTeamInfo(that.data.page);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      page_text: '正在加载',
      team_member: [],
      isReachBottom: false
    })
    that.getTeamInfo(that.data.page);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (!that.data.isReachBottom) {
      that.getTeamInfo(that.data.page);
    }
  },

  //切换
  changeState:function(e){
    let that = this;
    that.setData({
      state: e.target.dataset.state
    });
  },

  //获取用户信息
  getMemberInfo: function () {
    let that = this;
    core.request({
      url: 'api/member/info',
      method: 'GET',
      success: function (res) {
        var member = res.data.member;
        member.cash = (member.cash/100).toFixed(2);
        that.setData({
          member: res.data.member
        });
      },
      fail: function (res) {
      }
    });
  },

  // 请求团队信息
  getTeamInfo: function(page){
    let that = this;
    core.request({
      url: 'api/member/team',
      method: 'GET',
      data: {
        page: page,
        limit:5
      },
      success: function (res) {
        that.setData({
          leader:res.data.team.leader,
          me: res.data.team.me
        });

        var team_member = that.data.team_member;
        if (res.data.team.member.length < 5){
          for (let i = 0; i < res.data.team.member.length; i++) {
            team_member.push(res.data.team.member[i]);
          }
          that.setData({
            isReachBottom: true,
            page_text: '暂无更多成员',
            team_member: team_member
          });
        }else{
          for (let i = 0; i < res.data.team.member.length; i++) {
            team_member.push(res.data.team.member[i]);
          }
          that.setData({
            page: ++page,
            page_text: '加载更多',
            team_member: team_member
          });
        }
      },
      fail: function (res) {
      }
    });
  },

  onShareAppMessage: function () {
    let that = this;
    return {
      title: '加入' + that.data.leader.team_name+'团队',
      path: 'pages/index/index?scene=t-'+that.data.leader.id,
      imageUrl: ''
    }
  },

  //回首页
  backIndex: function () {
    wx.switchTab({
      url: '../index/index'
    });
  }

})