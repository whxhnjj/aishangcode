
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {    
    hostName: core.baseUrl,
    group_state: 0,
    group_name: '商品分组',
    page: 1,
    goods: [],
    original: [],
    isReachBottom: 1,
    load_more: '加载更多'
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      original: []
    })
    that.requestGroupPro(that.data.group_id, that.data.page, that.data.original);
    wx.stopPullDownRefresh()
  },

  //监听用户上拉触底
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        original: that.data.goods
      })
      that.requestGroupPro(that.data.group_id, that.data.page, that.data.original);
    }
  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      group_id: e.group_id,
    })
    that.requestGroupList();
    that.requestGroupPro(that.data.group_id, that.data.page, that.data.original);
  },

  onShow: function () {
    let that = this;
    that.setData({ group_state: 0})
  },

  //切换分组显示状态
  toggleGroupState:function(){
    let that = this;
    if (that.data.group_state == 0 ){
      that.setData({ group_state: 1 })
    }else{
      that.setData({ group_state: 0 })
    }
  },

  //更新分组信息
  resetProGroup:function(e){
    let that = this;
    var group_id = e.target.dataset.id;
    var index = e.target.id;
    that.setData({
      group_name: that.data.group[index].name,
      group_state: 0,
      group_id: group_id,
      page: 1,
      original:[],
      load_more: '加载更多'
    });
    that.requestGroupPro(that.data.group_id, that.data.page, that.data.original);
  },

  //请求分组数据
  requestGroupList: function(){
    let that = this;
    core.request({
      url: 'api/goods/group_lists',
      method: 'GET',
      success: function (res) {
        var group = res.data.group;
        for(var i=0;i<group.length;i++){
          if (that.data.group_id == group[i].id){
            that.setData({
              group_name: group[i].name
            })
          }
        }
        that.setData({
          group: group
        })
      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  //请求分组商品
  requestGroupPro: function (id, page,original){
    let that = this;
    core.request({
      url: 'api/goods/lists',
      method: 'GET',
      data: {
        group_id: id,
        page:page
      },
      success: function (res) {
        if (page == 1) {
          that.setData({
            goods:res.data.goods,
            isReachBottom: 1
          })
        }

        if (res.data.goods.length == 0) {
          that.setData({ isReachBottom: 0, load_more: '已到达我的底线' });
        } else {
          var n = original;
          for (var i = 0; i < res.data.goods.length; i++) {
            n.push(res.data.goods[i])
          }
          that.setData({
            goods: n,
          });
          page++;
          that.setData({
            page: page
          })
        }

      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  //用户分享
  onShareAppMessage: function () {
  }
})