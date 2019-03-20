
var app = getApp();

Page({
  data:{
    card_state:false,
    card_state_text: '查看'
  },

  onLoad: function (e) {
    var that = this;
    that.setData({
      isIphoneX: app.globalData.isIphoneX
    })
  },

  //名片展开折叠
  cardDetailState: function(){
    let that = this;
    if (that.data.card_state){
      that.setData({
        card_state: false,
        card_state_text: '查看'
      })
    }else{
      that.setData({
        card_state: true,
        card_state_text: '收起'
      })
    }
  },

  //拨打电话
  callNum: function(e){
    let that = this;
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.msg
    })
  },

  //复制
  copy: function (e) {
    console.log(e)
    wx.setClipboardData({
      data: e.target.dataset.msg,
      success: function (res) {
        wx.showToast({
          title: '复制成功',
          icon: 'none'
        })
      }
    })
  },

  //打开地图
  openMap:function(e){
    let that = this;
    var latlng = e.target.dataset.latlng.split(',');
    wx.openLocation({
      latitude: parseFloat(latlng[0]),
      longitude: parseFloat(latlng[1])
    })
  },

  //添加通讯录
  addPhoneContact: function(){
    wx.addPhoneContact({
      firstName: '李宝斌',
      mobilePhoneNumber: '13309218552',
      weChatNumber:'libaobin',
      email:'muzili@hercity.com',
      organization:'西安微智创想信息科技有限公司',
      title:'CEO',
      workAddressState:'陕西省',
      workAddressCity:'西安市',
      workAddressStreet:'高新区高新一路',
      success: function () {
        wx.showToast({
          title: '添加成功',
        })
      }
    })
  },


 
  onShareAppMessage: function () {
    return {
      title: 'Hi，我是微智创想的李宝斌。这是我的名片，请惠存。'
    }
  }
})
