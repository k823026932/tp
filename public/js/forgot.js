(function() {
  $.fn.popupCode = function(callback) {
    var bananaLeft, bananaTop;
    bananaLeft = ($$(window).width() - 316) / 2;
    bananaTop = ($$(window).height() - 300) / 2;
    return $(document).unfold({
      src: '../html/win-verificationcode',
      id: 'win-verifycode',
      title: '验证码',
      width: '316',
      height: 'auto',
      top: bananaTop,
      left: bananaLeft,
      curtain: true,
      callback: function() {
        return $.fn.popupCodeConfirm(function(captcha, closepopup) {
          return typeof callback === "function" ? callback(captcha, closepopup) : void 0;
        });
      }
    });
  };

  $(function() {
    return $.require('ready', function() {
      var comm, pathUrl, render, toHash;
      pathUrl = '';
      comm = function(num) {
        var line;
        line = $('#fNav').find('.lineWrap').eq(num);
        line.removeClass('history').prevAll().addClass('history');
        line.nextAll().removeClass('history active');
        return line.addClass('active');
      };
      toHash = function() {
        var k, ref, results, v;
        ref = $.query();
        results = [];
        for (k in ref) {
          v = ref[k];
          console.log(k, v);
          results.push($.hash(k, v));
        }
        return results;
      };
      render = function(forget) {
        var a, arr, forgot, j, len, results;
        toHash();
        forgot = forget ? forget : $.hash().forgot;
        arr = ['userName', 'mobileUser', 'mailUser', 'mailSuccess', 'setPw', 'success', 'mailsetPw', 'mailFaild'];
        results = [];
        for (j = 0, len = arr.length; j < len; j++) {
          a = arr[j];
          if (a === forgot) {
            switch (arr.indexOf(a)) {
              case 0:
                results.push($('#fNav').find('.lineWrap').eq(0).removeClass('history').addClass('active').siblings().removeClass('history active'));
                break;
              case 2:
              case 3:
                results.push(comm(1));
                break;
              case 1:
                results.push(comm(1));
                break;
              case 4:
                comm(2);
                results.push($('#setPw').show().siblings().hide());
                break;
              case 5:
                results.push(comm(3));
                break;
              case 6:
                comm(2);
                results.push($('#mailsetPw').show().siblings().hide());
                break;
              case 7:
                $('#fNav').hide();
                results.push($('#mailFaild').show().siblings().hide());
                break;
              default:
                results.push(void 0);
            }
          } else {
            results.push(void 0);
          }
        }
        return results;
      };
      render();
      (function() {
        var btnUser, codeInput, nameInput, replace;
        replace = function(str, start, end, rstr) {
          var a, i, j, len, newStr;
          newStr = '';
          for (i = j = 0, len = str.length; j < len; i = ++j) {
            a = str[i];
            if (i >= start && i <= end) {
              a = rstr.charAt(i - 1);
            }
            newStr += a;
          }
          return newStr;
        };
        codeInput = $('#userName .codeWrap input');
        nameInput = $('#userName .input input');
        nameInput.on('keyup', function() {
          if (nameInput.val().length.length !== "") {
            nameInput.css('border', '1px solid #bbb');
            return $('#userName .next').css("background", "#2d9ed8");
          }
        });
        codeInput.on('keyup', function() {
          if (nameInput.val().length.length !== "") {
            return codeInput.css('border', '1px solid #bbb');
          }
        });
        btnUser = $('#userName');
        btnUser.find('.codeWrap input').on('keydown', function(e) {
          btnUser.find('.btnWrap .next').css('background', '#2d9ed8');
          if (e.keyCode === 13) {
            return btnUser.find('.btnWrap .next').click();
          }
        });
        return btnUser.find('.btnWrap .next').on('click', function() {
          var that;
          that = $(this);
          if ($.trim(nameInput.val()) === "") {
            nameInput.css('border', '1px solid red');
            nameInput.info({
              text: '请输入手机号或邮箱!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if ($.trim(codeInput.val()) === "") {
            codeInput.css('border', '1px solid red');
            codeInput.info({
              text: '请输入验证码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          return $.get(pathUrl + '/checkcaptcha.aspx', {
            captcha: $.trim(codeInput.val())
          }).done(function(data) {
            if (data.success) {
              return $.get(pathUrl + '/checkMobileAndEmailInfo.aspx', {
                info: $.trim($('#userName .input input').val())
              }).done(function(data) {
                var codeBtn, mobileBtn, that_yz, userInfo, usernameData;
                usernameData = data.username;
                userInfo = $("#mobileUser");
                if (data.success) {
                  if (data.checkinfo === 'mobile') {
                    userInfo.find('.shouji').show().end().find('.nameWrap').show().end().find('.selectYz').show().find('span').show().text('手机验证');
                    $('#mobileUser').show().siblings().hide();
                    render('mobileUser');
                    (function() {
                      var codeBtn, codeHandler, timer;
                      codeBtn = $('#mobileUser .sendBtn');
                      timer = null;
                      codeBtn.bind('click', function() {
                        return codeHandler();
                      });
                      return codeHandler = function() {
                        return $.get(pathUrl + '/createAuthCode.aspx', {
                          username: usernameData
                        }).done(function(data) {
                          var num;
                          if (data.success) {
                            $.info('success', '发送成功');
                            if (typeof callback === "function") {
                              callback();
                            }
                            num = 59;
                            codeBtn.text('重新获取' + num + 's').addClass('disabled');
                            $(".shouji .mobileWrap .sendBtn").css("cursor", "auto");
                            return timer = setInterval(function() {
                              if (num > 1) {
                                codeBtn.text('重新获取' + (--num) + 's');
                                return codeBtn.unbind('click');
                              } else {
                                codeBtn.unbind('click').removeClass('disabled');
                                codeBtn.text('发送短信验证码');
                                $(".shouji .mobileWrap .sendBtn").css("cursor", "pointer");
                                clearInterval(timer);
                                return codeBtn.bind('click', function() {
                                  return codeHandler();
                                });
                              }
                            }, 1000);
                          } else {
                            if (typeof callback === "function") {
                              callback(data.result);
                            }
                            codeBtn.info({
                              text: data.result,
                              type: 'warning',
                              fadeout: 1e3,
                              direction: 'right'
                            });
                          }
                        });
                      };
                    })();
                    mobileBtn = $('#mobileUser');
                    codeBtn = $('#mobileUser .sendBtn');
                    mobileBtn.find('.mobileWrap input').on('keydown', function(e) {
                      if (e.keyCode === 13) {
                        return mobileBtn.find('.shouji .next').click();
                      }
                    });
                    mobileBtn.find('.shouji .next').click(function() {
                      $('#mobileUser').data('authCode', $.trim($('#mobileUser .mobileWrap input').val()));
                      return $.post(pathUrl + '/verifyAuthCode.aspx', {
                        username: codeBtn.attr('username'),
                        authCode: $.trim($('#mobileUser .mobileWrap input').val())
                      }).done(function(data) {
                        if (data.success) {
                          $.info('success', data.result);
                          $('#setPw').show().siblings().hide();
                          return render('setPw');
                        } else {
                          return $.info('error', data.result);
                        }
                      });
                    });
                  } else if (data.success && data.checkinfo === 'email') {
                    $.info('success', '发送成功');
                    $('#mailSuccess').show().siblings().hide();
                    render('mobileUser');
                  }
                  $('.yzUser').find('.yzMobile span').text(data.mobileNum);
                  return $('#mobileUser .sendBtn').attr('username', usernameData);
                } else {
                  that_yz = nameInput;
                  if (/^1[3|4|5|8][0-9]{9}$/.test($.trim(nameInput.val()))) {
                    that_yz.parent('.input').info({
                      text: '该手机未注册!',
                      type: 'warning',
                      fadeout: 1e3,
                      direction: 'right'
                    });
                    return that_yz.css('border', '1px solid red');
                  } else if (/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test($.trim(nameInput.val()))) {
                    that_yz.parent('.input').info({
                      text: '该邮箱未注册!',
                      type: 'warning',
                      fadeout: 1e3,
                      direction: 'right'
                    });
                    return that_yz.css('border', '1px solid red');
                  } else {
                    that_yz.parent('.input').info({
                      text: '请输入正确的手机号或邮箱!',
                      type: 'warning',
                      fadeout: 1e3,
                      direction: 'right'
                    });
                    return that_yz.css('border', '1px solid red');
                  }
                }
              }).fail(function() {
                var that_yz;
                $.info('error', '错误');
                that.css('background', '#999');
                that_yz = nameInput;
                if (/^1[3|4|5|8][0-9]{9}$/.test($.trim(nameInput.val()))) {
                  that_yz.parent('.input').info({
                    text: '该手机未注册!',
                    type: 'warning',
                    fadeout: 1e3,
                    direction: 'right'
                  });
                  return that_yz.css('border', '1px solid red');
                } else if (/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test($.trim(nameInput.val()))) {
                  that_yz.parent('.input').info({
                    text: '该邮箱未注册!',
                    type: 'warning',
                    fadeout: 1e3,
                    direction: 'right'
                  });
                  return that_yz.css('border', '1px solid red');
                } else {
                  that_yz.parent('.input').info({
                    text: '请输入正确的手机号或邮箱!',
                    type: 'warning',
                    fadeout: 1e3,
                    direction: 'right'
                  });
                  return that_yz.css('border', '1px solid red');
                }
              });
            } else {
              $.info('error', data.result);
              that.css('background', '#999');
              codeInput.css('border', '1px solid red');
              codeInput.info({
                text: '请输入正确的验证码!',
                type: 'warning',
                fadeout: 1e3,
                direction: 'right'
              });
              return btnUser.find('.codeWrap span').click();
            }
          }).fail(function() {
            $.info('error', '错误');
            return that.css('background', '#999');
          });
        });
      })();
      (function() {
        var flag, setPw;
        setPw = $('#setPw');
        flag = true;
        setPw.find('.pwd input').on('blur', function() {
          var that;
          that = $(this);
          if ($.trim(that.val()).length >= 6 && $.trim(that.val()).length <= 32) {
            flag = true;
            return that.css('border', '1px solid green');
          } else if ($.trim(that.val()).length < 6 && $.trim(that.val()).length > 0) {
            that.css('border', '1px solid red');
            ($(this)).parent().info({
              text: '密码不能小于6个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          } else if ($.trim(that.val()).length > 32) {
            that.css('border', '1px solid red');
            ($(this)).parent().info({
              text: '密码不能超过32个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
        });
        setPw.find('.qrpwd input').on('blur', function() {
          var that;
          that = $(this);
          if ($.trim(that.val()) === "" && $.trim($('.pwd input').val()) !== "") {
            that.css('border', '1px solid red');
            that.parent().info({
              text: '请重复输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if (that.val() === setPw.find('.pwd input').val() && $.trim($('.pwd input').val()).length >= 6 && $.trim($('.pwd input').val()).length <= 32) {
            that.css('border', '1px solid green');
            return flag = true;
          } else if (that.val() !== setPw.find('.pwd input').val() && $.trim($('.pwd input').val()).length >= 6 && $.trim($('.pwd input').val()).length <= 32 && $.trim(that.val()).length !== 0) {
            that.css('border', '1px solid red');
            flag = false;
            return ($(this)).parent().info({
              text: '两次输入的密码不一致',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
        });
        setPw.find('.qrpwd input').on('keydown', function(e) {
          if (e.keyCode === 13) {
            return setPw.find('.next').click();
          }
        });
        return setPw.find('.next').on('click', function() {
          var that;
          that = $(setPw.find('.pwd input'));
          if ($.trim(setPw.find('.pwd input').val()) === "") {
            that.css('border', '1px solid red');
            that.parent().info({
              text: '请输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if ($.trim(setPw.find('.pwd input').val()).length < 6 && $.trim(setPw.find('.pwd input').val()).length > 0) {
            that.css('border', '1px solid red');
            that.parent().info({
              text: '密码不能小于6个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          } else if ($.trim(setPw.find('.pwd input').val()).length > 32) {
            that.css('border', '1px solid red');
            that.parent().info({
              text: '密码不能超过32个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if ($.trim(setPw.find('.qrpwd input').val()).length === 0 && $.trim(setPw.find('.pwd input').val()) !== "") {
            setPw.find('.qrpwd input').css('border', '1px solid red');
            $(setPw.find('.qrpwd input')).parent().info({
              text: '请重复输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if (setPw.find('.qrpwd input').val() !== setPw.find('.pwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            setPw.find('.qrpwd input').css('border', '1px solid red');
            flag = false;
            $(setPw.find('.qrpwd input')).parent().info({
              text: '两次输入的密码不一致',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
          if (setPw.find('.pwd input').val() === setPw.find('.qrpwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            return $.post(pathUrl + '/recoveryPassword.aspx', {
              username: $('#mobileUser .sendBtn').attr('username'),
              password: $.trim(setPw.find('.pwd input').val()),
              authCode: $('#mobileUser').data('authCode')
            }).done(function(data) {
              var num;
              if (data.success) {
                $.info('success', '修改成功');
                $('#success').show().siblings().hide();
                render('success');
                num = 4;
                $('#success h1 span').text(--num);
                return setInterval(function() {
                  --num;
                  if (num <= 0) {
                    return window.location.href = 'http://www.acfun.cn/';
                  } else {
                    return $('#success h1 span').text(num);
                  }
                }, 1e3);
              } else {
                return setPw.find('.qrpwd input').trigger('keyup');
              }
            });
          }
        });
      })();
      return (function() {
        var flag, setPw;
        setPw = $('#mailsetPw');
        flag = true;
        setPw.find('.pwd input').on('blur', function() {
          var that;
          that = $(this);
          if ($.trim(that.val()).length >= 6 && $.trim(that.val()).length <= 32) {
            flag = true;
            return that.css('border', '1px solid green');
          } else if ($.trim(that.val()).length < 6 && $.trim(that.val()).length > 0) {
            that.css('border', '1px solid red');
            ($(this)).parent().info({
              text: '密码不能小于6个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          } else if ($.trim(that.val()).length > 32) {
            that.css('border', '1px solid red');
            ($(this)).parent().info({
              text: '密码不能超过32个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
        });
        setPw.find('.qrpwd input').on('blur', function() {
          var that;
          that = $(this);
          if ($.trim(that.val()) === "" && $.trim(setPw.find('.pwd input').val()) !== "") {
            that.css('border', '1px solid red');
            that.parent().info({
              text: '请重复输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if (that.val() === setPw.find('.pwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            that.css('border', '1px solid green');
            flag = true;
          }
          if (that.val() !== setPw.find('.pwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            that.css('border', '1px solid red');
            flag = false;
            return $(setPw.find('.qrpwd input')).parent().info({
              text: '两次输入的密码不一致',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
        });
        setPw.find('.qrpwd input').on('keydown', function(e) {
          if (e.keyCode === 13) {
            return setPw.find('.next').click();
          }
        });
        return setPw.find('.next').on('click', function() {
          var that1;
          that1 = $(setPw.find('.pwd input'));
          if ($.trim(setPw.find('.pwd input').val()) === "") {
            that1.css('border', '1px solid red');
            that1.parent().info({
              text: '请输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if ($.trim(setPw.find('.pwd input').val()).length < 6 && $.trim(setPw.find('.pwd input').val()).length > 0) {
            that1 = $(setPw.find('.pwd input'));
            that1.css('border', '1px solid red');
            that1.parent().info({
              text: '密码不能小于6个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          } else if ($.trim(setPw.find('.pwd input').val()).length > 32) {
            that1.css('border', '1px solid red');
            that1.parent().info({
              text: '密码不能超过32个字符!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if ($.trim(setPw.find('.qrpwd input').val()) === "" && $.trim(setPw.find('.pwd input').val()) !== "") {
            setPw.find('.qrpwd input').css('border', '1px solid red');
            $(setPw.find('.qrpwd input')).parent().info({
              text: '请重复输入新密码!',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
            return;
          }
          if (setPw.find('.qrpwd input').val() !== setPw.find('.pwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            setPw.find('.qrpwd input').css('border', '1px solid red');
            flag = false;
            $(setPw.find('.qrpwd input')).parent().info({
              text: '两次输入的密码不一致',
              type: 'warning',
              fadeout: 1e3,
              direction: 'right'
            });
          }
          if (setPw.find('.pwd input').val() === setPw.find('.qrpwd input').val() && $.trim(setPw.find('.pwd input').val()).length >= 6 && $.trim(setPw.find('.pwd input').val()).length <= 32) {
            return $.post(pathUrl + '/recoveryPassword.aspx', {
              username: $.hash().username,
              type: 'email',
              password: $.trim(setPw.find('.pwd input').val()),
              key: $.hash().key,
              uid: $.hash().uid
            }).done(function(data) {
              var num;
              if (data.success) {
                $.info('success', '修改成功');
                $('#success').show().siblings().hide();
                render('success');
                num = 4;
                $('#success h1 span').text(--num);
                return setInterval(function() {
                  --num;
                  if (num <= 0) {
                    return window.location.href = 'http://www.acfun.cn/';
                  } else {
                    return $('#success h1 span').text(num);
                  }
                }, 1e3);
              } else {
                return setPw.find('.qrpwd input').trigger('keyup');
              }
            });
          }
        });
      })();
    });
  });

}).call(this);
