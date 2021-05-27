<?php
/**
 * 数组助手类
 * @project helper
 * @copyright
 * @author yuanzhumc
 * @version 1.0.0
 * @date: 2021/5/27
 * @createTime: 14:52
 * @filename Arrays.php
 * @product_name PhpStorm
 * @link
 * @example
 */


namespace yuanzhumc\helper;


class Arrays
{
    /**
     * 移除数组中值为空的数据
     * @param array $arr
     * @param bool $trim
     */
    static function removeEmpty(array & $arr, bool $trim = true) {

        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) {
                self::removeEmpty ( $arr [$key] );
            } else {
                $value = trim ( $value );
                if ($value == '') {
                    unset ( $arr [$key] );
                } elseif ($trim) {
                    $arr [$key] = $value;
                }
            }
        }
    }

    /**移除数组中指定键的数据
     * @param array $array
     * @param array $keys
     * @return array
     */
    static function removeKey(array &$array, array $keys) :array {

        if (! is_array ( $keys )) {
            $keys = array (
                $keys
            );
        }
        return array_diff_key ( $array, array_flip ( $keys ) );
    }

    /**
     * 将一个平面的二维数组按照指定的字段转换为树状结构
     * @param array $arr 数据源
     * @param string|int $key_node_id 节点ID字段名
     * @param string|int $key_parent_id 节点父ID字段名
     * @param string $key_childrens 保存子节点的字段名
     * @param false $treeIndex
     * @param boolean|null $refs 是否在返回结果中包含节点引用
     * @return array 树形结构的数组
     */
    static function toTree(array $arr, $key_node_id, $key_parent_id = 'parent_id', string $key_childrens = 'children', bool $treeIndex = false, bool & $refs = null) :array {

        $refs = array();
        foreach ($arr as $offset => $row) {
            $arr[$offset][$key_childrens] = array();
            $refs[$row[$key_node_id]] = & $arr[$offset];
        }

        $tree = array();
        foreach ($arr as $offset => $row) {
            $parent_id = $row[$key_parent_id];
            if ($parent_id) {
                if (!isset($refs[$parent_id])) {
                    if ($treeIndex) {
                        $tree[$offset] = & $arr[$offset];
                    }
                    else {
                        $tree[] = & $arr[$offset];
                    }
                    continue;
                }
                $parent = & $refs[$parent_id];
                if ($treeIndex) {
                    $parent[$key_childrens][$offset] = & $arr[$offset];
                }
                else {
                    $parent[$key_childrens][] = & $arr[$offset];
                }
            }
            else {
                if ($treeIndex) {
                    $tree[$offset] = & $arr[$offset];
                }
                else {
                    $tree[] = & $arr[$offset];
                }
            }
        }

        return $tree;
    }

    /**
     * 将数组按照键值转换成数组
     * @param array $arr
     * @param string $key_field
     * @param string|null $value_field
     * @return array
     */
    static function toHashmap(array $arr, string $key_field, string $value_field = null) :array {

        $ret = array ();
        if (empty ( $arr )) {
            return $ret;
        }
        if ($value_field) {
            foreach ( $arr as $row ) {
                if (isset ( $row [$key_field] )) {
                    $ret [$row [$key_field]] = isset($row [$value_field])?$row [$value_field]:'NULL';
                }
            }
        } else {
            foreach ( $arr as $row ) {
                $ret [$row [$key_field]] = $row;
            }
        }
        return $ret;
    }

    /**
     * 将数组用分隔符连接并输出
     * @param array $array
     * @param string $separator
     * @param string $find
     * @return string
     */
    static function toString(array $array, string $separator = ',', string $find = '') :string {

        $str = '';
        $separator_temp = '';

        if (! empty ( $find )) {
            if (! is_array ( $find )) {
                $find = self::toArray ( $find );
            }
            foreach ( $find as $key ) {
                $str .= $separator_temp . $array [$key];
                $separator_temp = $separator;
            }
        } else {
            foreach ( $array as $value ) {
                $str .= $separator_temp . $value;
                $separator_temp = $separator;
            }
        }
        return $str;
    }

    /**
     * 从一个二维数组中返回指定键的所有值
     * @param array $arr
     * @param string $col
     * @return array
     */
    static function getCols(array $arr, string $col) :array {

        $ret = array ();
        foreach ( $arr as $row ) {
            if (isset ( $row [$col] )) {
                $ret [] = $row [$col];
            }
        }
        return $ret;
    }
}