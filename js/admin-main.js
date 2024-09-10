const nameProperty = 'name';
const genderProperty = 'gender';
const statusProperty = 'status';

const theme = {
    colors: {
        maleBadgeBackground: '#A8D07A',
        maleBadgeText: '#000000',
        princePrincessBorder: '#666666',
        civilianBorder: '#666666',
        personText: '#666666',
        personNodeBackground: 'rgba(168,208,122,0.25)',
        link: '#666666'
    },
    fonts: {
        badgeFont: 'bold 8px Montserrat',
        birthDeathFont: '10px Montserrat',
        nameFont: '800 13px Montserrat',
    }
};
const onMouseEnterPart = (e, part) => part.isHighlighted = true;
const onMouseLeavePart = (e, part) => {
    if (!part.isSelected) part.isHighlighted = false;
}
const onSelectionChange = (part) => {
    part.isHighlighted = part.isSelected;
}
const STROKE_WIDTH = 1;
const CORNER_ROUNDNESS = 18;

function strokeStyle(shape) {
    return shape
        .set({
            fill: theme.colors.personNodeBackground,
            strokeWidth: STROKE_WIDTH
        })
        .bind('stroke', statusProperty, (status) => {
            return status === 'king' ? theme.colors.princePrincessBorder : theme.colors.civilianBorder;
        })
        .bind('fill', theme.colors.personNodeBackground);  // Устанавливаем цвет фона
}

const genderToText = (gender) => (gender === 'M' ? 'Ақпарат' : 'Ақпарат');
const genderToTextColor = (gender) => gender === 'M' ? theme.colors.maleBadgeText : theme.colors.femaleBadgeText;
const genderToFillColor = (gender) => gender === 'M' ? theme.colors.maleBadgeBackground : theme.colors.femaleBadgeBackground;
const personBadge = () => {
    // Создаем панель значка
    const badge = new go.Panel('Auto',
        {
            alignmentFocus: go.Spot.TopRight,
            alignment: new go.Spot(1, 0, -25, STROKE_WIDTH - 0.5),
            visible: true  // Сначала скрыто
        }).add(new go.Shape({
            figure: 'RoundedRectangle', parameter1: CORNER_ROUNDNESS,
            desiredSize: new go.Size(NaN, 23.5),
            stroke: null
        }).bind('fill', genderProperty, genderToFillColor),
        new go.TextBlock({
            font: theme.fonts.badgeFont
        }).bind('stroke', genderProperty, genderToTextColor)
            .bind('text', genderProperty, genderToText)
    );

    // Обработчик клика на значке
    badge.mouseEnter = onMouseEnterPart;
    badge.mouseLeave = onMouseLeavePart;
    badge.click = (e, obj) => {
        openInfo(obj.part.data.id)
        e.handled = true;
    };

    return badge;
};
const personBirthDeathTextBlock = () => new go.TextBlock(
    {
        stroke: theme.colors.personText,
        font: theme.fonts.birthDeathFont,
        alignmentFocus: go.Spot.Top,
        alignment: new go.Spot(0.5, 1.12, 0, -35)
    }).bind('text', '', ({born, death}) => {
    if (!born) return ''; return `${born} - ${death ?? ''}`;
})
const personMainShape = () => new go.Shape({
    figure: 'RoundedRectangle',
    desiredSize: new go.Size(150, 65),
    portId: '',
    parameter1: CORNER_ROUNDNESS
}).apply(strokeStyle);
const personNameTextBlock = () => new go.TextBlock({
    stroke: theme.colors.personText,
    font: theme.fonts.nameFont,
    desiredSize: new go.Size(145, 55),
    overflow: go.TextOverflow.Ellipsis,
    textAlign: 'center',
    verticalAlignment: go.Spot.Center,
    toolTip: go.GraphObject.build('ToolTip').add(new go.TextBlock({margin: 4}).bind('text', nameProperty)),
    alignmentFocus: go.Spot.Top,
    alignment: new go.Spot(0.5, -0.29, 0, 25)
}).bind('text', nameProperty)
const createNodeTemplate = () => new go.Node('Spot',
    {
        selectionAdorned: false,

        selectionChanged: onSelectionChange,
        movable: false,
        click: async (e, node) => {
            if (node.isTreeExpanded) {
                // Скрываем детей
                diagram.commandHandler.collapseTree(node);
            } else {
                // Загружаем данные и разворачиваем узел
                try {
                    const result = await fetchAndAddFamilyData(node.data.id, node.data.name);
                    console.log(result);
                    if (result) {
                        diagram.model.addNodeDataCollection(result);
                        diagram.updateAllTargetBindings();  // Обновляем диаграмму
                    }
                    diagram.commandHandler.expandTree(node);
                } catch (error) {
                    console.error('Error fetching family data:', error);
                }
            }
            if (!e.handled) {
                e.handled = true;
            }
        }

    }).add(new go.Panel('Spot').add(
        personMainShape(),
        personNameTextBlock(),
        personBirthDeathTextBlock()),
    personBadge()

);
async function fetchAndAddFamilyData(id, name) {
    try {
        const response = await fetch(`http://atatek.com/php/tree/get_items.php?id=${id}`);
        const result = await response.json();

        if (result.status) {
            const newMembers = result.data
                .map(member => ({
                    id: parseInt(member.id),
                    name: member.name,
                    gender: 'M',
                    status: 'status',
                    born: member.birth_year,
                    death: member.death_year,
                    parent: parseInt(member.parent_id),
                    info: member.info
                }));

            if (newMembers.length === 0) {
                // Вызов функции, если данных нет
                noDate(name);
                return [];
            }

            const existingIds = new Set(familyData.map(member => member.id));
            const filteredNewMembers = newMembers.filter(member => !existingIds.has(member.id));

            if (filteredNewMembers.length > 0) {
                familyData.push(...filteredNewMembers);
                return filteredNewMembers;
            }
        } else {
            console.error('Error in API response');
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }

    return [];
}


const createLinkTemplate = () => new go.Link({
    selectionAdorned: false,
    routing: go.Routing.Orthogonal,
    layerName: 'Background',
    mouseEnter: onMouseEnterPart,
    mouseLeave: onMouseLeavePart
}).add(new go.Shape({
    figure: 'RoundedRectangle', parameter1: CORNER_ROUNDNESS,
    stroke: theme.colors.link,
    strokeWidth: 1
}));
let diagram;
const initDiagram = (divId) => {
    diagram = new go.Diagram(divId, {
        layout: new go.TreeLayout({
            angle: 90,
            nodeSpacing: 20,
            layerSpacing: 50,
            layerStyle: go.TreeLayout.LayerUniform,
            treeStyle: go.TreeStyle.LastParents,
            alternateAngle: 90,
            alternateLayerSpacing: 35,
            alternateAlignment: go.TreeAlignment.BottomRightBus,
            alternateNodeSpacing: 20
        }),
        'toolManager.hoverDelay': 100,
        linkTemplate: createLinkTemplate(),
        model: new go.TreeModel({
            nodeKeyProperty: 'id'
        })
    });

    diagram.nodeTemplate = createNodeTemplate();
    diagram.model.nodeDataArray = familyData;
    const root = diagram.findNodeForKey(14);
    diagram.scale = 0.75;
    diagram.scrollToRect(root.actualBounds);

};
window.addEventListener('DOMContentLoaded', () => {
    initDiagram('myDiagramDiv');
    setTimeout(() => {

    }, 300);
});

