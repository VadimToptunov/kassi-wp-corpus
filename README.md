# Kassi WP Corpus

Minimal before/after code pairs from real, fixed WordPress plugin vulnerabilities. Each case backs
one rule of [kassi-wp-rules](https://github.com/VadimToptunov/kassi-wp-rules). The rule must flag
`vulnerable.php` and stay silent on `fixed.php`.

```
<RULE-ID>/case-NN/
  vulnerable.php   a few lines around the sink, before the fix (verbatim excerpt)
  fixed.php        the same code after the fix (verbatim excerpt)
  meta.yml         cve, platform, plugin_slug, version_before/after, file, fix_commit, advisories, pattern
```

- Snippets are verbatim excerpts of GPL plugins from the wordpress.org directory, each with an SPDX
  header naming its source and CVE. Context is elided, not rewritten.
- `pattern.fix_added` is what the fix introduced (`current_user_can`, `wp_verify_nonce`, `esc_*`,
  `$wpdb->prepare`, ...). That is the defence the rule has to recognise.
- Facts come from NVD/cve.org, GitHub Security Advisories, Wordfence, and the plugin's own SVN/Git
  history. WPScan and Patchstack data is not stored here.

## License

The excerpts keep their upstream license, GPL-2.0-or-later. Everything else in this repository is
under the same license ([LICENSE](LICENSE)).
