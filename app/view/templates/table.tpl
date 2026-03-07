<style>
{literal}
  body {
    font-family: sans-serif;
  }
  table {
  background: #FFFFFF;
  margin-bottom: 1.25rem;
  border: solid 1px #DDDDDD;
  table-layout: auto; }
  table caption {
    background: transparent;
    color: #222222;
    font-size: 1rem;
    font-weight: bold; }
  table thead {
    background: #F5F5F5; }
    table thead tr th, table thead tr td {
      padding: 0.5rem 0.625rem 0.625rem;
      font-size: 0.875rem;
      font-weight: bold;
      color: #222222; }
  table tfoot {
    background: #F5F5F5; }
    table tfoot tr th, table tfoot tr td {
      padding: 0.5rem 0.625rem 0.625rem;
      font-size: 0.875rem;
      font-weight: bold;
      color: #222222; }
  table tr th, table tr td {
    padding: 0.5625rem 0.625rem;
    font-size: 0.875rem;
    color: #222222;
    text-align: left; }
  table tr.even, table tr.alt, table tr:nth-of-type(even) {
    background: #F9F9F9; }
  table thead tr th, table tfoot tr th, table tfoot tr td, table tbody tr th, table tbody tr td, table tr td {
    display: table-cell;
    line-height: 1.125rem; }
{/literal}
</style>
<table style="width:100%;">
  <thead>
  {foreach from=$keys item=key}
    <th>{$key}</th>
  {/foreach}
  </thead>
  <tbody>
    {foreach from=$rows item=row}
    <tr>
      {foreach from=$keys item=key}
        <td>{$row[$key]}</td>
      {/foreach}
    </tr>
    {/foreach}
  </tbody>
</table>