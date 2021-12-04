describe("shortcode params", function() {

  describe("default_channel", function() {

    cases([
      ['empty param', '', ''],
      ['trims input', ' general ', 'general'],
    ]).it("parses", function(desc, input, expected) {
      spyOn(wpslacksync.shortcode, 'shortcodeParam').and.callFake(function(paramName) {
        if (paramName != 'default_channel') {
          throw "invalid param " + paramName;
        }
        return input;
      });
      expect(wpslacksync.shortcode.parseDefaultChannel()).toEqual(expected);
    });

  });

  describe("allowed_channels", function() {

    cases([
      ['empty param', '', []],
      ['single channel', 'general', ['general']],
      ['multiple channels space-separated', 'general random', ['general', 'random']],
      ['trims additional whitespace space-separated', ' general  random ', ['general', 'random']],
      ['multiple channels comma-separated', 'general,random', ['general', 'random']],
      ['trims additional whitespace comma-separated', ' general , random ', ['general', 'random']],
    ]).it("parses", function(desc, input, expected) {
      spyOn(wpslacksync.shortcode, 'shortcodeParam').and.callFake(function(paramName) {
        if (paramName != 'allowed_channels') {
          throw "invalid param " + paramName;
        }
        return input;
      });
      expect(wpslacksync.shortcode.parseAllowedChannels()).toEqual(expected);
    });

  });

  describe("allowed_private_channels", function() {

    cases([
      ['empty param', '', []],
      ['single channel', 'general', ['general']],
      ['multiple channels space-separated', 'general random', ['general', 'random']],
      ['trims additional whitespace space-separated', ' general  random ', ['general', 'random']],
      ['multiple channels comma-separated', 'general,random', ['general', 'random']],
      ['trims additional whitespace comma-separated', ' general , random ', ['general', 'random']],
    ]).it("parses", function(desc, input, expected) {
      spyOn(wpslacksync.shortcode, 'shortcodeParam').and.callFake(function(paramName) {
        if (paramName != 'allowed_private_channels') {
          throw "invalid param " + paramName;
        }
        return input;
      });
      expect(wpslacksync.shortcode.parseAllowedPrivateChannels()).toEqual(expected);
    });

  });

  describe("no_file_upload", function() {

    cases([
      ['empty param', '', false],
      ['true string is true', 'true', true],
      ['true string with space around is true', ' true ', true],
      ['other string is false', 'false', false],
      ['other string is false', 'off', false],
      ['other string is false', 'on', false],
      ['other string is false', 'some_string', false],
    ]).it("parses", function(desc, input, expected) {
      spyOn(wpslacksync.shortcode, 'shortcodeParam').and.callFake(function(paramName) {
        if (paramName != 'no_file_upload') {
          throw "invalid param " + paramName;
        }
        return input;
      });
      expect(wpslacksync.shortcode.parseNoFileUpload()).toEqual(expected);
    });

  });

});
