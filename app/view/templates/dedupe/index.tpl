{include file="header2.tpl" title="Company Create"}

<div ng-controller="DedupeController">
  
  <table>
    <tr>
      <td>Status:</td>
      <td>#(status.siteDuplicates.status)</td>
    </tr>
    <tr>
      <td>Date ran:</td>
      <td>#(status.siteDuplicates.date)</td>
    </tr>
    <tr>
      <td>Matches:</td>
      <td>#(status.siteDuplicates.match_count)</td>
    </tr>
    <tr>
      <td>Mismatches:</td>
      <td>#(status.siteDuplicates.mismatch_count)</td>
    </tr>
    <tr>
      <td>Additions Selected:</td>
      <td>#(status.siteDuplicates.addition_count)</td>
    </tr>
  </table>
  
  <form ng-submit="search()">
    <input type="text" ng-model="searchInput" placeholder="search">
    <button class="tiny btn" type="submit">Search</button>
  </form>

  <form ng-submit="get()">
    <input type="text" ng-model="page" placeholder="page">
    <button class="tiny btn" type="submit">Go</button>
  </form>

  <table class="adminlist">
    <thead>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Name</th>
        <th>Addr1</th>
        <th>Postcode</th>
      </tr>
    </thead>
    <tbody ng-repeat="m in mismatches">
      <tr ng-click="getMismatch(this)">
        <td>#(m.company_id)</td>
        <td>#(m.type)</td>
        <td>#(m.name)</td>
        <td>#(m.addr1)</td>
        <td>#(m.postcode)</td>
      </tr>
      <tr ng-show="show">
        <td colspan="5">
          <div ng-hide="mismatch">Loading...</div>
          <div ng-show="mismatch" class="dedupe-inner">
            <div class="right">
              <button class="success btn" ng-click="save(m)">Save</button>
              <button class="alert btn" ng-click="remove(m)">Remove</button>
            </div>
            <table class="adminlist hover">
              <thead>
                <tr>
                  <th></th>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Addr1</th>
                  <th>Postcode</th>
                </tr>
              </thead>
              <tbody>
                <tr class="match">
                  <td></td>
                  <td>#(m.company_id)</td>
                  <td>#(m.name)</td>
                  <td>#(m.addr1)</td>
                  <td>#(m.postcode)</td>
                </tr>
                <tr ng-repeat="c in mismatch.matched_companies" ng-click="selectMatch(this)" ng-class="selected ? 'selected' : ''">
                  <td><input type="checkbox" ng-model="selected" ng-click="selectMatch(this)"></td>
                  <td>#(c.id)</td>
                  <td>#(c.name)</td>
                  <td>#(c.address.address_1)</td>
                  <td>#(c.address.postcode)</td>
                </tr>
              </tbody>
            </table>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <button ng-click="last()" class="btn tiny left">Last page</button>
  <button ng-click="next()" class="btn tiny right">Next page</button>

</div>

{include file="footer2.tpl"}