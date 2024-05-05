<?php
namespace dacode\dao;

class Requests {
    // Charge tout sauf le code (qui est chargé au dernier moment car potentiellement lourd)
    public const SELECT_PLAYG_WORKSPACES_BY_USER_ID = "select WkPlayg.id_wk, slot_idx_wk, name_wk, workspace.crea_wk, workspace.modif_wk from WkPlayg inner join workspace on WkPlayg.id_wk = workspace.id_wk where id_user = ? order by slot_idx_wk asc";
    public const SELECT_PLAYG_WORKSPACE_BY_USER_ID_AND_SLOT = "select workspace.id_wk from workspace	inner join WkPlayg on workspace.id_wk = WkPlayg.id_wk where id_user = :user_id and slot_idx_wk = :slot_idx";
    public const FUNC_CREATE_PLAYG_WORKSPACE = "select create_playg_workspace(:user_id, :name_wk, :slot_idx) as id_wk";
    public const DEL_PLAYG_WORKSPACE = "delete from WkPlayg where id_wk = :id_wk";
    public const SELECT_CODE_FROM_PLAYG_SLOT = "select code.id_lang, code.id_wk, data_cod, name_lang, editor_lang, slot_idx_wk, id_user from code inner join workspace on code.id_wk = workspace.id_wk inner join WkPlayg on code.id_wk = WkPlayg.id_wk inner join langage on code.id_lang = langage.id_lang where slot_idx_wk = :slot_idx and id_user = :user_id";
    public const INSERT_CODE_IN_WORKSPACE = "insert into code (id_wk, id_lang, data_cod) values
    (:id_wk, :id_lang, :data_code)";
    public const DELETE_CODE_BY_WORKSPACE_ID = "delete from code where id_wk = :id_wk";
    public const UPDATE_PLAYG_WORKSPACE_NAME = "update WkPlayg set name_wk = :name_wk where id_wk = :id_wk";
    public const ADD_USER_ACCOUNT = "insert into userprofile (id_sub, pwd_user, role_user, mail_user, pseudo_user) values (:id_sub, :pwd_user, :role_user, :mail_user, :pseudo_user)";
    public const DELETE_USER_BY_PSEUDO = "delete from userprofile where pseudo_user = :pseudo_user";
    public const SELECT_USER_BY_PSEUDO = "select id_user, userprofile.id_sub, pwd_user, role_user, mail_user, pseudo_user, date_sub, name_sub, type_sub from userprofile inner join subscription on userprofile.id_sub = subscription.id_sub where pseudo_user = ?";
    public const SELECT_USER_BY_ID = "select id_user, userprofile.id_sub, pwd_user, role_user, mail_user, pseudo_user, date_sub, name_sub, type_sub from userprofile inner join subscription on userprofile.id_sub = subscription.id_sub where id_user = ?";
    public const VERIFY_USER_BY_PSEUDO = "select id_user from userprofile where pseudo_user = ?";
    public const VERIFY_USER_BY_MAIL = "select id_user from userprofile where mail_user = ?";
    
    
    public const SELECT_LANGAGE_BY_NAME = "select id_lang, name_lang, editor_lang from langage where name_lang = :name_lang"; // A VERIFIER !!!!!!!!!!!!!

    //################################//
    //############ AIMANE ############//
    //################################//
    //CRUD CATEGORIES //
    public const SELECT_CATEGORIES = "select name_cat, id_cat from category";
    public const SELECT_CATEGORY_BY_ID = "select name_cat, id_cat from category where id_cat = :id_cat";
    public const INSERT_CATEGORY = "insert into category (name_cat, id_cat) values (:name, :id)";
    public const DELETE_CATEGORY = "delete from category where id_cat = :id_cat";
    public const UPDATE_CATEGORY = "update category set name_cat = :name_cat where id_cat = :id_cat";
    //CRUD CHAPTER //
    public const SELECT_CHAPTERS_BY_CATEGORY = "select id_ch, title_ch from chapter join category on chapter.id_cat = category.id_cat where category.id_cat = :categoryId";
    public const SELECT_CHAPTER_BY_ID = "select id_ch, title_ch from chapter where id_ch = :id_ch";
    public const ADD_CHAPTER = "insert into Chapter (title_ch,id_cat) values (:title_ch,:id_cat)";
    public const DELETE_CHAPTER_BY_ID = "delete from Chapter where id_ch = :id_ch";
    public const UPDATE_CHAPTER = "update chapter set title_ch = :title_ch where id_ch = :id_ch";

    //CRUD LANGAGE //
    public const SELECT_LANGAGES = "select id_lang, name_lang, editor_lang from langage";
    public const SELECT_LANGAGE_BY_ID = "select id_lang, name_lang, editor_lang from langage where id_lang = :id_lang";
    public const INSERT_LANGAGE = "insert into langage (id_lang, name_lang, editor_lang) values (:id_lang, :name_lang,:editor_lang)";
    public const DELETE_LANGAGE = "delete from langage where id_lang = :id_lang";
    public const UPDATE_LANGAGE = "update langage set name_lang = :name_lang, editor_lang = :editor_lang where id_lang = :id_lang";

    //CRUD LESSON //
    public const SELECT_LESSONS_BY_CHAPTER_ID = "select lesson.id_les, lesson.title_les, lesson.instr_les, lesson.id_sub, chapter.id_ch
        from lesson 
        join chapter ON lesson.id_ch = chapter.id_ch 
        where lesson.id_ch = :chapterId";
    public const ADD_LESSON_BY_CHAPTER = "insert into Lesson (title_les,instr_les,id_sub,id_ch) values (:title_les,:instr_les,:id_sub,:id_ch)";
    public const DELETE_LESSON_BY_ID = "delete from Lesson where id_les = :id_les";
    public const UPDATE_LESSON = "update lesson set title_les = :title_les, instr_les = :instr_les, id_sub = :id_sub where id_les = :id_les";
    public const SELECT_GOAL_BY_LESSON = "select * from Goal where id_les = ?";
    //PAS NECESSAIRE CAR C'EST UPDATE ET DELETE EN CASCADE 
    // public const INSERT_GOAL = "insert into goal (descr_goal, condi_goal, id_les) values (:descr_goal, :condi_goal, :id_les)";
    // public const DELETE_GOAL = "delete from goal where id_goal = :id_goal";


}